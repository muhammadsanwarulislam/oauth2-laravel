export interface Notification {
  id: number
  message: string
  type: 'success' | 'error' | 'info' | 'warning'
}

export const useNotification = () => {
  const notifications = useState<Notification[]>('notifications', () => [])

  const add = (message: string, type: Notification['type'] = 'info') => {
    const id = Date.now()
    notifications.value.push({ id, message, type })

    setTimeout(() => {
      remove(id)
    }, 3000)
  }

  const remove = (id: number) => {
    notifications.value = notifications.value.filter(n => n.id !== id)
  }

  return {
    notifications: readonly(notifications),
    add,
    remove
  }
}