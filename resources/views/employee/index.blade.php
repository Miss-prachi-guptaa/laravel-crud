@extends('layouts.app')

@section('content')


<div class="header">
    <h2><i class="fa-solid fa-users"></i> Employee Management</h2>

    <div class="date-box">
        <i class="fa-solid fa-calendar"></i>
        27 June 2025
    </div>
</div>

<div class="card">

<h3 class="card-title">
<i class="fa-solid fa-user-plus"></i>
Add / Edit Employee
</h3>

<input type="hidden" id="employee_id">

<div class="form-grid">

<div class="input-group">
<label>Name</label>
<input type="text" id="name" placeholder="Enter Name">
</div>

<div class="input-group">
<label>Email</label>
<input type="email" id="email" placeholder="Enter Email">
</div>

<div class="input-group">
<label>Phone</label>
<input type="text" id="phone" placeholder="Enter Phone">
</div>

<div class="input-group">
<label>Department</label>
<input type="text" id="department" placeholder="Department">
</div>

<div class="input-group">
<label>Status</label>

<select id="status">
<option>Active</option>
<option>Inactive</option>
</select>

</div>

</div>

<div class="btn-area">

<button id="saveBtn">
<i class="fa-solid fa-floppy-disk"></i>
Save Employee
</button>

<button class="resetBtn">
Reset
</button>

</div>

</div>

<div id="message"></div>

<div class="card">

<h3 class="card-title">

<i class="fa-solid fa-list"></i>

Employee List

</h3>

<div class="table-top">

<input
type="text"
placeholder="Search Employee...">

</div>

<table>

<thead>

<tr>

<th>Name</th>

<th>Email</th>

<th>Phone</th>

<th>Department</th>

<th>Status</th>

<th>Action</th>

</tr>

</thead>

<tbody id="employeeTable">

</tbody>

</table>

</div>

@endsection