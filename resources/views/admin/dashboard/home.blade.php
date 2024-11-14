@extends('admin.dashboard.layout.master')

@section('content')
<div class="container-fluid">

   
    <!-- Dashboard Header -->
    <h2>Dashboard Overview</h2>
    <p>Welcome to the admin dashboard for your e-commerce site!</p>

    <!-- Metrics Section -->
    <div class="row">
        <!-- Total Revenue -->
        <div class="col-md-3">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Revenue</h5>
                    <p class="card-text">$25,000</p>
                </div>
            </div>
        </div>

        <!-- Orders -->
        <div class="col-md-3">
            <div class="card text-white bg-info mb-3">
                <div class="card-body">
                    <h5 class="card-title">Orders</h5>
                    <p class="card-text">320</p>
                </div>
            </div>
        </div>

        <!-- Total Products -->
        <div class="col-md-3">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Products</h5>
                    <p class="card-text">150</p>
                </div>
            </div>
        </div>

        <!-- Registered Customers -->
        <div class="col-md-3">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Customers</h5>
                    <p class="card-text">2000</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="card mt-4">
        <div class="card-header">
            <h5>Recent Orders</h5>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Order ID</th>
                        <th scope="col">Customer</th>
                        <th scope="col">Amount</th>
                        <th scope="col">Status</th>
                        <th scope="col">Date</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>001</td>
                        <td>John Doe</td>
                        <td>$120</td>
                        <td><span class="badge bg-success">Completed</span></td>
                        <td>2023-01-15</td>
                    </tr>
                    <tr>
                        <td>002</td>
                        <td>Jane Smith</td>
                        <td>$80</td>
                        <td><span class="badge bg-warning">Pending</span></td>
                        <td>2023-01-17</td>
                    </tr>
                    <tr>
                        <td>003</td>
                        <td>Mike Johnson</td>
                        <td>$60</td>
                        <td><span class="badge bg-danger">Cancelled</span></td>
                        <td>2023-01-20</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection