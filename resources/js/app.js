import forms from './forms';
import emailShare from './email-share';
import pdfExport from './pdf-export';

require('./bootstrap');

// Wait for the DOM to load
document.addEventListener('DOMContentLoaded', () => {
  forms();
  emailShare();
  pdfExport();
});
