function csrfToken() {
  const metaCsrfToken = document.querySelector('meta[name=csrf-token]');
  if (metaCsrfToken) {
    return metaCsrfToken.getAttribute('content');
  }

  return null;
}

module.exports = {
  csrfToken,
};
