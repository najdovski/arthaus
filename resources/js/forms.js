export default function main() {
  const forms = document.querySelectorAll('form.js--validate');

  // Bootstap form validation
  forms?.forEach(form => {
    form.setAttribute('novalidate', true);
    form.addEventListener('submit', event => {
      if (!form.checkValidity()) {
        event.preventDefault();
        event.stopPropagation();
      } else {
        return false;
      }

      form.classList.add('was-validated');
    });

    const textArea = form.querySelector('textarea');
    if (textArea) {
      /* Using JS instead of ternary operator in blade to trim the
      white spaces around the value, for the sake of readability */
      textArea.value = textArea.value.trim();
    }

    // Datetime validation
    const startedAtInput = form.querySelector('input[name="started-at"]');
    const finishedAtInput = form.querySelector('input[name="finished-at');

    if (!startedAtInput || !finishedAtInput) return;

    // Started at validation
    startedAtInput.addEventListener('change', () => {
      finishedAtInput.min = startedAtInput.value;
    });

    // Finished at validation
    finishedAtInput.addEventListener('change', () => {
      const now = new Date();
      const finishedAtInputDate = new Date(finishedAtInput.value);

      if (finishedAtInputDate < now) {
        startedAtInput.max = finishedAtInput.value;
      }
    });
  });
}
