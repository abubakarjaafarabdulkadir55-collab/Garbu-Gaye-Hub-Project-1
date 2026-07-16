document.addEventListener('DOMContentLoaded', function () {

  // Mobile nav toggle
  var toggle = document.querySelector('.nav-toggle');
  var links  = document.querySelector('.nav-links');
  if (toggle && links) {
    toggle.addEventListener('click', function () {
      links.classList.toggle('open');
    });
  }

  // Booking widget / booking page: keep check-out after check-in
  var checkIn  = document.querySelector('[name="check_in"]');
  var checkOut = document.querySelector('[name="check_out"]');
  if (checkIn && checkOut) {
    var today = new Date().toISOString().split('T')[0];
    checkIn.min = today;
    checkOut.min = today;

    checkIn.addEventListener('change', function () {
      var nextDay = new Date(checkIn.value);
      nextDay.setDate(nextDay.getDate() + 1);
      var minOut = nextDay.toISOString().split('T')[0];
      checkOut.min = minOut;
      if (checkOut.value && checkOut.value <= checkIn.value) {
        checkOut.value = minOut;
      }
    });
  }

  // Auto-dismiss flash alerts after a few seconds
  document.querySelectorAll('.alert').forEach(function (el) {
    setTimeout(function () {
      el.style.transition = 'opacity .4s ease';
      el.style.opacity = '0';
      setTimeout(function () { el.remove(); }, 400);
    }, 5000);
  });

  // Live nights + total price calculation on booking page
  var pricePerNight = document.body.getAttribute('data-price');
  var nightsOut = document.getElementById('nightsOut');
  var totalOut  = document.getElementById('totalOut');
  function recalc() {
    if (!checkIn || !checkOut || !pricePerNight || !nightsOut) return;
    if (!checkIn.value || !checkOut.value) return;
    var d1 = new Date(checkIn.value);
    var d2 = new Date(checkOut.value);
    var nights = Math.round((d2 - d1) / (1000 * 60 * 60 * 24));
    if (nights > 0) {
      nightsOut.textContent = nights + (nights === 1 ? ' night' : ' nights');
      totalOut.textContent = (nights * parseFloat(pricePerNight)).toFixed(2);
    } else {
      nightsOut.textContent = '—';
      totalOut.textContent = '0.00';
    }
  }
  if (checkIn && checkOut) {
    checkIn.addEventListener('change', recalc);
    checkOut.addEventListener('change', recalc);
    recalc();
  }
});
