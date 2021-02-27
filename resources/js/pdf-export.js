import html2pdf from 'html2pdf.js';

export default function pdfExport() {
  const pdfExportButton = document.querySelector('#pdf-export-button');
  const reportsToPrint = document.querySelector('#reports-print');

  if (!pdfExportButton || !reportsToPrint) return;

  pdfExportButton.addEventListener('click', () => {
    const options = {
      margin: 10,
      filename: 'Activities_Report.pdf',
    };

    html2pdf().set(options).from(reportsToPrint).save();
  });
}
