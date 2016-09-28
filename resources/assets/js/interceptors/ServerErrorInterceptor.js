import { NotificationStore } from '../stores/NotificationStore.js'

export default (request, next) => {
  next((response) => {
    // Handle unprocessable entity (HTTP 422)
    if (response.status === 500) {
      NotificationStore.setNotification({
        text: 'Ocorreu um erro inesperado. Tente novamente mais tarde.',
        type: 'danger',
        timeout: false
      })
    }
  })
}
