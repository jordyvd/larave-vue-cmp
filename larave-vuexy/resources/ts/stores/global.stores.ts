
export const globalStores = defineStore('global', {
  state: () => ({
    dialog: {
      show: false,
      title: 'Error',
      message: 'An error has occurred',
      color: 'error',
      icon: 'tabler-alert-triangle',
    },
    showLoading: false,
  }),
  actions: {
    setShowDialogError(value: boolean, message: string = 'An error has occurred') {
      this.dialog.show = value;
      this.dialog.title = 'Error';
      this.dialog.message = message
      this.dialog.icon = 'tabler-alert-triangle';
      this.dialog.color = 'error';
    },
    setShowLoading(value: boolean) {
      this.showLoading = value;
    }
  }
});
