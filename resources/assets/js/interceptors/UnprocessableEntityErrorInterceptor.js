import { NotificationStore } from '../stores/NotificationStore.js'

export default (request, next) => {
  next((response) => {
    // Handle unprocessable entity (HTTP 422)
    if (response.status === 422) {
      NotificationStore.setNotification({
        text: 'Existem alguns problemas com os dados introduzidos.',
        type: 'danger'
      })
    }
  })
}
