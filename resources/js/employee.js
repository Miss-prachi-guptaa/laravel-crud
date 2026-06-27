
// Start the file
$(document).ready(function () {

    loadEmployees();

    $('#saveBtn').click(function () {
        saveEmployee();
    });

});


// Create loadEmployees()


function loadEmployees() {

    $.ajax({

        url: '/api/employees',
        type: 'GET',

        success: function (response) {

            let rows = '';

            response.data.forEach(function (employee) {

                rows += `
                <tr>

                    <td>${employee.name}</td>

                    <td>${employee.email}</td>

                    <td>${employee.phone}</td>

                    <td>${employee.department}</td>

                    <td>
                        <span class="status">
                            ${employee.status}
                        </span>
                    </td>

                    <td>

                        <button
                            class="edit"
                            onclick="editEmployee(${employee.id})">

                            Edit

                        </button>

                        <button
                            class="delete"
                            onclick="deleteEmployee(${employee.id})">

                            Delete

                        </button>

                    </td>

                </tr>
                `;

            });

            $('#employeeTable').html(rows);

        }

    });

}



//save employee

function saveEmployee() {

    let id = $('#employee_id').val();

    if (id == '') {

        createEmployee();

    } else {

        updateEmployee(id);

    }

}


// getEmployeeData()

function getEmployeeData() {

    return {

        name: $('#name').val(),

        email: $('#email').val(),

        phone: $('#phone').val(),

        department: $('#department').val(),

        status: $('#status').val()

    };

}


// Create createEmployee()

function createEmployee() {

    let employee = getEmployeeData();

    $.ajax({

        url: '/api/employees',

        type: 'POST',

        data: employee,

        success: function (response) {

            showMessage(response.message);

            resetForm();

            loadEmployees();

        },

        error: function (xhr) {

            showErrors(xhr);

        }

    });

}


// Create updateEmployee()

function updateEmployee(id) {

    let employee = getEmployeeData();

    $.ajax({

        url: '/api/employees/' + id,

        type: 'PUT',

        data: employee,

        success: function (response) {

            showMessage(response.message);

            resetForm();

            loadEmployees();

        },

        error: function (xhr) {

            showErrors(xhr);

        }

    });

}


// Create editEmployee()

function editEmployee(id) {

    $.ajax({

        url: '/api/employees/' + id,

        type: 'GET',

        success: function (response) {

            let emp = response.data;

            $('#employee_id').val(emp.id);

            $('#name').val(emp.name);

            $('#email').val(emp.email);

            $('#phone').val(emp.phone);

            $('#department').val(emp.department);

            $('#status').val(emp.status);

        }

    });

}

// Create deleteEmployee()

function deleteEmployee(id) {

    if (!confirm("Are you sure?")) {

        return;

    }

    $.ajax({

        url: '/api/employees/' + id,

        type: 'DELETE',

        success: function (response) {

            showMessage(response.message);

            loadEmployees();

        }

    });

}

function resetForm() {

    $('#employee_id').val('');

    $('#name').val('');

    $('#email').val('');

    $('#phone').val('');

    $('#department').val('');

    $('#status').val('Active');

}

// Create showMessage()

function showMessage(message) {

    $('#message').html(

        `<span style="color:green;font-weight:bold;">
            ${message}
        </span>`

    );

}

// Create showErrors()

function showErrors(xhr) {

    let errors = xhr.responseJSON.errors;

    let msg = '';

    for (let key in errors) {

        msg += errors[key][0] + '\n';

    }

    alert(msg);

}


// Call loadEmployees() on page load





