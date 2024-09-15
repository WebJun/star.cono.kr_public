class Toast {
  $bvToast = null

  setBvToast = ($bvToast) => {
    this.$bvToast = $bvToast
  }

  send = (msg, variant) => {
    const variants = [
      'primary',
      'secondary',
      'danger',
      'warning',
      'success',
      'info'
    ]
    if (!variants.includes(variant)) {
      variant = 'default'
    }

    let title = '알림'
    if (variant !== 'default') {
      title = variant
    }

    this.$bvToast.toast(msg, {
      title,
      autoHideDelay: 5000,
      appendToast: false,
      variant
    })
  }
}

export default new Toast()
