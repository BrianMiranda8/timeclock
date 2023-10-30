function setId() {
    let targetSelect = event.target;
    let input = document.querySelector('input[data-hidden-input]');
    input.value = targetSelect.options[targetSelect.selectedIndex];
}