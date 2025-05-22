<?php

declare(strict_types=1);

namespace Repository;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

abstract class BaseRepository
{
    abstract function model();

    public function getAll($offset, $limit, $searchData = null, $option = null)
    {
        // Check if the environment is production
        if (app()->environment('production')) {
            // Create a unique cache key based on the parameters
            $cacheKey = $this->generateCacheKey($offset, $limit, $searchData, $option);
            $countCacheKey = $cacheKey . '_count';

            // Try to get the result and count from the cache
            list($result, $totalCount, $message) = $this->getCachedData($cacheKey, $countCacheKey, $offset, $limit, $searchData, $option);
        } else {
            // If the environment is not production, fetch data directly without using Redis
            $result = $this->getDataFromDatabase($offset, $limit, $searchData, $option);
            $totalRecords = $this->model()::count(); 
            $totalCount = $limit > 0 ? floor($totalRecords / $limit) : 0;
            $message = 'Data fetched directly from the database in non-production environment.';
        }

        return ['result' => $result, 'count' => $totalCount, 'metadata' => $this->metadata($totalCount, $message)];
    }

    private function getCachedData($cacheKey, $countCacheKey, $offset, $limit, $searchData, $option)
    {
        // Initialize the message
        $message = '';

        // Attempt to retrieve data from the cache
        $result = Cache::get($cacheKey);
        $totalCount = Cache::get($countCacheKey);

        if (!$result || !$totalCount) {
            // If data is not found in the cache, fetch it from the database
            $result = $this->getDataFromDatabase($offset, $limit, $searchData, $option);
            $totalRecords = $this->model()::count(); 
            $totalCount = $limit > 0 ? floor($totalRecords / $limit) : 0;

            // Cache the result and total count
            $cacheDuration = 60;
            Cache::put($cacheKey, $result, $cacheDuration);
            Cache::put($countCacheKey, $totalCount, $cacheDuration);

            // Set message indicating data is loaded from the database
            $message = 'Data loaded from the database and cached.';
        } else {
            // Set message indicating data is loaded from the cache
            $message = 'Data loaded from the cache.';
        }

        return [$result, $totalCount, $message];
    }

    private function getDataFromDatabase($offset, $limit, $searchData, $option)
    {
        // Fetch data from the database based on the provided parameters
        $query = $this->model()::query();
        $this->applyDefaultCriteria($query);

        switch ($option) {
            case 'list':
                $result = $this->paginateResult($query, $offset, $limit);
                break;

            case 'search':
                if ($searchData) {
                    $this->applySearchCriteria($query, $searchData);
                }
                $result = $this->paginateResult($query, $offset, $limit);
                break;

            default:
                $result = $query->get();
                break;
        }

        if ($result->isEmpty()) {
            throw new \RuntimeException('No records found.');
        }

        return $result;
    }

    private function generateCacheKey($offset, $limit, $searchData, $option)
    {
        return 'model_' . $offset . '_' . $limit . '_' . md5(json_encode($searchData)) . '_' . $option;
    }

    public function metadata($row, $responseType)
    {
        return [
            'API Version' => '1.0.1',
            'Response Time' => date('Y-m-d H:i:s'),
            'Data Response Type' => $responseType,
            'Row Count' => $row,
            'Content Type' => 'application/json',
        ];
    }

    protected function applyDefaultCriteria($query)
    {
        $query->orderBy('created_at', 'desc');
    }

    protected function applySearchCriteria($query, $searchData)
    {
        $searchFields = $this->getSearchFields();
        $query->where(function ($query) use ($searchFields, $searchData) {
            foreach ($searchFields as $field) {
                $query->orWhere($field, 'like', '%' . $searchData . '%');
            }
        });
    }

    protected function paginateResult($query, $offset, $limit)
    {
        return $query->offset(($offset - 1) * $limit)
            ->limit($limit)
            ->get();
    }

    protected function getTotalCount($query, $limit)
    {
        return $query->paginate($limit)->total();
    }

    protected function getSearchFields()
    {
        return [];
    }

    public function getArrtibuteValueByColumnNameAndValue($columnName, $value)
    {
        return $this->model()::where($columnName, $value)->first();
    }

    public function findByID($id): Model
    {
        $record = $this->model()::find($id);
        if (!$record) {
            throw new \Exception("Record with ID {$id} has no data in the database.");
        }
        return $record;
    }

    public function findOrFailByID($id): Model
    {
        return $this->model()::findOrFail($id);
    }

    public function findOrFailByEmail($email): Model
    {
        return $this->model()::where('email',$email)->first();
    }

    public function findOrFailByPhone($phone): Model
    {
        return $this->model()::where('phone',(int)$phone)->first();
    }

    public function create(array $modelData)
    {
        return $this->model()::create($modelData);
    }

    public function updateByID($id, array $modelData)
    {
        $model = $this->findOrFailByID($id);
        $model->update($modelData);
        $model->refresh();
        return $model;
    }

    public function deletedByID($id)
    {
        $model = $this->findOrFailByID($id);
        return $model->delete();
    }

    function updateByModelCondition($condition, $field, $value)
    {
        return $this->model()::where($condition)->update([$field => $value]);
    }

    public function setTranslatableData($model, $field, $value, $lang)
    {
        $model->setTranslation($field, $value, $lang);
        $model->save();
    }

    public function makeCurlRequest(string $url, array $options = []): array
    {
        // Initialize cURL session
        $ch = curl_init($url);

        // Default cURL options
        $defaultOptions = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        ];

        // Merge default options with user-defined options
        curl_setopt_array($ch, $defaultOptions + $options);

        // Execute the request
        $responseBody = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);

        // Close cURL session
        curl_close($ch);

        // Return the response data as an array
        return [
            'status' => $httpCode,
            'body' => $responseBody,
            'error' => $curlError,
        ];
    }
}
