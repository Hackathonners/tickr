export const NotificationStore = {
  state: [],

  setNotification: function (notification) {
    if (this.state.length > 0) {
      this.state.$remove(this.state.pop())
    }

    this.state.push(notification)
  },
  removeNotification: function () {
    this.state.$remove(this.state.pop())
  }
}
