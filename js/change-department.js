function assignDepartment(this) {
    let departmentButton = event.target
    let newDepartment = document.querySelector('form>input[name="newDepartment"]')
    newDepartment.value = departmentButton.getAttribute('data-department');
}