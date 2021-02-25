export default function main() {
  const emailShareModal = document.querySelector('#modal-email-share');
  const startedAtInput = document.querySelector('input[name="started-at"]');
  const finishedAtInput = document.querySelector('input[name="finished-at');
  const currentPage = document.querySelector('.pagination .page-item.active .page-link');
  if (!emailShareModal || !startedAtInput || !finishedAtInput) return;
  
  const form = emailShareModal.querySelector('form');
  form.addEventListener('submit', () => {
    const emailStartedAt = form.querySelector('input[name="email-started-at"]');
    const emailFinishedAt = form.querySelector('input[name="email-finished-at"]');
    const emailPageNumber = form.querySelector('input[name="email-current-page"]');

    emailStartedAt.value = startedAtInput.value;
    emailFinishedAt.value = finishedAtInput.value;
    emailPageNumber.value = currentPage ? currentPage.innerText.trim() : '';
  });
}
