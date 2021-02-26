import flatpickr from 'flatpickr';

export function flatpickrActivateActivities() {
  const initialStartAtElement = document.querySelector('input[name="started-at"]');
  const initialFinishedAtElement = document.querySelector('input[name="finished-at"]');

  if (!initialStartAtElement || !initialFinishedAtElement) return;

  const startedAtFlatpickr = flatpickr('input[name="started-at"]', {
    enableTime: true,
    altInput: true,
    altFormat: 'j F Y (H:i)',
    dateFormat: 'Y-m-dTH:i',
    maxDate: initialStartAtElement.max,
    minDate: initialStartAtElement.min,
    onChange: function(dateobj) {
      const dateFormatted = formatDate(dateobj);
      finishedAtFlatpickr.set('minDate', dateFormatted);
    },
  });

  const finishedAtFlatpickr = flatpickr('input[name="finished-at"]', {
    enableTime: true,
    altInput: true,
    altFormat: 'j F Y (H:i)',
    dateFormat: 'Y-m-dTH:i',
    maxDate: initialFinishedAtElement.max,
    minDate: initialFinishedAtElement.min,
    onChange: (dateobj) => {
      const dateFormatted = formatDate(dateobj);
      startedAtFlatpickr.set('maxDate', dateFormatted);
    }
  });
}

export function flatpickrActivateReports() {
  const initialStartDate = document.querySelector('input[name="start-date"]');
  const initialEndDate = document.querySelector('input[name="end-date"]');

  if (!initialStartDate || !initialEndDate) return;

  const startDate = flatpickr('input[name="start-date"]', {
    altInput: true,
    altFormat: 'j F Y',
    dateFormat: 'Y-m-d',
    maxDate: initialStartDate.max,
    minDate: initialStartDate.min,
    onChange: function(dateobj) {
      const dateFormatted = formatDate(dateobj);
      finishedAtFlatpickr.set('minDate', dateFormatted);
    },
  });

  const finishedAtFlatpickr = flatpickr('input[name="end-date"]', {
    altInput: true,
    altFormat: 'j F Y',
    dateFormat: 'Y-m-d',
    maxDate: initialEndDate.max,
    minDate: initialEndDate.min,
    onChange: (dateobj) => {
      const dateFormatted = formatDate(dateobj);
      startDate.set('maxDate', dateFormatted);
    }
  });
}

function formatDate(dateobj) {
  const date = new Date(dateobj);

  const year = date.getFullYear();
  const month = ('0'+(date.getMonth()+1)).slice(-2);
  const day = ('0' + date.getDate()).slice(-2);
  const hour = ('0' + date.getHours()).slice(-2);
  const minutes = ('0' + date.getMinutes()).slice(-2);

  const formatted = `${year}-${month}-${day}T${hour}:${minutes}`
  return formatted;
}