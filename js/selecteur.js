    document.addEventListener('DOMContentLoaded', function() {
    const select = document.getElementById('select_type');
    select.addEventListener('change', function() {
        if (this.value) {
            location.href = this.value;
        }
    });

    // loop through select options
    // and if page URL contains option value, select it
    for (let i = 0; i < select.options.length; i++) {
        if (location.href.includes(select.options[i].value)) {
            select.value = select.options[i].value;
            break;
        }
    }
});//select_type
    document.addEventListener('DOMContentLoaded', function() {
    const select = document.getElementById('select_marque');
    select.addEventListener('change', function() {
        if (this.value) {
            location.href = this.value;
        }
    });

    // loop through select options
    // and if page URL contains option value, select it
    for (let i = 0; i < select.options.length; i++) {
        if (location.href.includes(select.options[i].value)) {
            select.value = select.options[i].value;
            break;
        }
    }
});//select_marque
    document.addEventListener('DOMContentLoaded', function() {
    const select = document.getElementById('select_modele');
    select.addEventListener('change', function() {
        if (this.value) {
            location.href = this.value;
        }
    });

    // loop through select options
    // and if page URL contains option value, select it
    for (let i = 0; i < select.options.length; i++) {
        if (location.href.includes(select.options[i].value)) {
            select.value = select.options[i].value;
            break;
        }
    }
});//select_modele