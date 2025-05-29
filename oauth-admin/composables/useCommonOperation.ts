export function useCommonOperation() {
  const isLoading         = ref(false);
  const isSuccess         = ref(false);
  const isValidation      = ref(false);
  const validationErrors  = ref<any>({});
  const currentPage       = ref(1);
  const totalPage         = ref(0);
  const limit             = ref(5);
  const message           = ref('');

  const fetchData = async (list: any, endpoint: string) => {
    isLoading.value = true; 
    isSuccess.value = false;

    try {
      const response: any = await $http(endpoint, {
        method: "GET",
      });

      list.value = response.data;
      isSuccess.value = true;

    } catch (error: any) {

      console.error(error);

    } finally {

      isLoading.value = false; 
    }
  };

  const createItem = async (
    form: any,
    itemList: any,
    endpoint: string,
    responseKey: string,
    validationRules: any
  ) => {
    const isValid = validateForm(form, validationRules);

    if (!isValid) {
      isValidation.value = true;
      return;
    }

    isLoading.value = true; 
    isSuccess.value = false; 

    try {
      const response: any = await $http(endpoint, {
        method: "POST",
        body: form,
      });
      console.log('create response',response);
      itemList.unshift(response.data[responseKey]);
      
      if (response.code === 201) {
        isSuccess.value = true; 
        isValidation.value = false;
        message.value = response.message;
      }
    } catch (error: any) {

      message.value = error.data;

    } finally {
      
      isLoading.value = false; 
    }
  };

  const fetchDataByID = async (
    id: any,
    endpoint: string,
    form: any,
    formMapping: any
  ) => {
    isLoading.value = true; 
    try {
      const response: any = await $http(`${endpoint}/${id}`, {
        method: "GET",
      });

      let data = response.data;

      for (const key in formMapping) {
        form.value[key] = getNestedValue(data, formMapping[key]);
      }
      
      isSuccess.value = true; 
    } catch (error) {
      console.error(error);
    } finally {
      isLoading.value = false; 
    }
  };

  function getNestedValue(obj: any, path: string) {
    return path.split(".").reduce((acc, part) => acc && acc[part], obj);
  }

  const updateItem = async (
    id: any,
    form: any,
    itemList: any,
    endpoint: string,
    responseKey: string,
    validationRules: any
  ) => {
    const isValid = validateForm(form, validationRules);

    if (!isValid) {
      isValidation.value = true;
      return;
    }

    isLoading.value = true; 
    isSuccess.value = false; 

    try {
      const response: any = await $http(`${endpoint}/${id}`, {
        method: "PUT",
        body: form,
      });

      const index = itemList.findIndex((item: any) => item.id === id);
      if (index !== -1) {
        itemList[index] = response.data[responseKey];
        isSuccess.value = true; 
      }
      
    } catch (error: any) {
      console.error(error.data?.message);
    } finally {
      isLoading.value = false; 
    }
  };

  const validateForm = (form: any, rules: any) => {
    validationErrors.value = {};
    let valid = true;

    for (const field in rules) {
      const rule = rules[field];
      if (rule.required && !form[field]) {
        valid = false;
        validationErrors.value[field] = `${field} is required`;
      }
    }

    return valid;
  };

  async function fetchDataWithLimition(endpoint: string, listRef: { value: never[]; }, customLimit?: number, searchData?: string, offset?: number) {
    isLoading.value = true; 
  
    try {
      const calculatedOffset = offset !== undefined ? offset : (currentPage.value - 1) * (customLimit || limit.value);
      
      const url = `${endpoint}?option=search&offset=${calculatedOffset}&limit=${customLimit || limit.value}${searchData ? `&searchData=${searchData}` : ''}`;
      
      const response: any = await $http(url, { method: 'GET' });
      
      listRef.value = response.data; 
      totalPage.value = response.total;
  
      isSuccess.value = true; 
  
    } catch (error) {
      listRef.value = []; 
      console.error(error); 
      
     } finally {
       isLoading.value= false; 
     }
   }

   async function deleteOperation(endpoint: any,id: any) {
     isLoading.value= true; 
     try {
       const response:any= await $http(`/${endpoint}/${id}`, { method:'DELETE' });
       if(response.code===200){
         message.value=response.message;
         isSuccess.value=true; 
       }else{
         message.value=response.message;
       }
     } catch (error:any) { 
       console.error(error.data?.message); 
     } finally { 
       isLoading.value=false; 
     }
   }

   return { 
     fetchData, 
     createItem, 
     fetchDataByID, 
     updateItem, 
     deleteOperation, 
     fetchDataWithLimition, 
     isLoading, 
     isSuccess, 
     isValidation, 
     validationErrors, 
     currentPage, 
     totalPage, 
     limit, 
     message 
   }; 
}
