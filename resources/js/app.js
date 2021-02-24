import forms from './forms';

require('./bootstrap');

// Wait for the DOM to load
document.addEventListener('DOMContentLoaded', () => {
  forms();
});
