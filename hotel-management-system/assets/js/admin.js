document.addEventListener('DOMContentLoaded', function () {

  // Generic modal open/close via data attributes:
  // data-open-modal="modalId"  /  data-close-modal
  document.querySelectorAll('[data-open-modal]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var modal = document.getElementById(btn.getAttribute('data-open-modal'));
      if (modal) modal.classList.add('open');
    });
  });
  document.querySelectorAll('[data-close-modal]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      btn.closest('.modal-bg').classList.remove('open');
    });
  });
  document.querySelectorAll('.modal-bg').forEach(function (bg) {
    bg.addEventListener('click', function (e) {
      if (e.target === bg) bg.classList.remove('open');
    });
  });

  // Populate the "Edit room" modal from a table row's data attributes
  document.querySelectorAll('[data-edit-room]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var form = document.getElementById('roomForm');
      if (!form) return;
      form.querySelector('[name="id"]').value = btn.dataset.id;
      form.querySelector('[name="room_number"]').value = btn.dataset.number;
      form.querySelector('[name="room_type_id"]').value = btn.dataset.typeId;
      form.querySelector('[name="floor"]').value = btn.dataset.floor;
      form.querySelector('[name="status"]').value = btn.dataset.status;
      form.querySelector('[name="image_url"]').value = btn.dataset.image;
      document.getElementById('roomModalTitle').textContent = 'Edit Room ' + btn.dataset.number;
      document.getElementById('roomModal').classList.add('open');
    });
  });

  // Simple client-side search filter for data tables
  document.querySelectorAll('[data-table-search]').forEach(function (input) {
    input.addEventListener('input', function () {
      var table = document.getElementById(input.getAttribute('data-table-search'));
      var term = input.value.toLowerCase();
      table.querySelectorAll('tbody tr').forEach(function (row) {
        row.style.display = row.textContent.toLowerCase().includes(term) ? '' : 'none';
      });
    });
  });

  // Confirm destructive actions
  document.querySelectorAll('[data-confirm]').forEach(function (el) {
    el.addEventListener('click', function (e) {
      if (!confirm(el.getAttribute('data-confirm'))) e.preventDefault();
    });
  });
});
