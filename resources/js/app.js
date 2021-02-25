import forms from './forms';
import emailShare from './email-share';

require('./bootstrap');

// Wait for the DOM to load
document.addEventListener('DOMContentLoaded', () => {
  forms();
  emailShare();
});
