// Menampilkan hint format Rupiah (mis. "Rp 1.000.000") di bawah kotak input
// nominal uang, mengikuti pola hint "mm/dd/yyyy" pada input tanggal — agar
// pengguna tahu nilai yang mereka ketik tanpa perlu mengubah tipe/markup input.
(function () {
    var KEYWORD_PATTERN = /(jumlah|total|harga|dana|sumber|nominal|biaya)/i;
    var EXCLUDE_PATTERN = /(volume|tanggal)/i;

    function formatRupiah(value) {
        var number = parseFloat(value);
        if (isNaN(number)) {
            return "";
        }
        var rounded = Math.round(number);
        var formatted = rounded.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        return "Rp " + formatted;
    }

    function ensureHint(input) {
        var next = input.nextElementSibling;
        if (next && next.classList && next.classList.contains("rupiah-hint")) {
            return next;
        }
        var hint = document.createElement("p");
        hint.className = "rupiah-hint";
        hint.style.color = "#838996";
        hint.style.fontWeight = "500";
        input.parentNode.insertBefore(hint, input.nextSibling);
        return hint;
    }

    function updateHint(input) {
        var hint = ensureHint(input);
        var text = formatRupiah(input.value);
        hint.textContent = text;
        hint.style.display = text ? "block" : "none";
    }

    var watched = [];

    function attach(input) {
        if (input.dataset.rupiahHintAttached) {
            return;
        }
        input.dataset.rupiahHintAttached = "true";
        updateHint(input);
        input.addEventListener("input", function () {
            updateHint(input);
        });
        input.addEventListener("change", function () {
            updateHint(input);
        });
        watched.push(input);
    }

    function init() {
        var inputs = document.querySelectorAll('input[type="number"]');
        inputs.forEach(function (input) {
            var key = (input.id || "") + " " + (input.name || "");
            if (KEYWORD_PATTERN.test(key) && !EXCLUDE_PATTERN.test(key)) {
                attach(input);
            }
        });
    }

    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", init);
    } else {
        init();
    }

    // Beberapa kotak (mis. "Jumlah", "Jumlah Sumber Dana") diisi otomatis
    // via JS perhitungan (hitungTotal/hitungTotalSumberDana) yang mengubah
    // ".value" secara langsung tanpa memicu event "input"/"change". Polling
    // ringan ini memastikan hint-nya tetap ikut terbarui meski nilainya
    // diubah secara terprogram, tanpa perlu menyentuh skrip perhitungan
    // yang sudah ada di tiap halaman.
    setInterval(function () {
        watched.forEach(function (input) {
            var hint = ensureHint(input);
            var text = formatRupiah(input.value);
            if (hint.textContent !== text) {
                hint.textContent = text;
                hint.style.display = text ? "block" : "none";
            }
        });
    }, 400);
})();
