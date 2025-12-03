@extends('layouts.app')

@section('section')
<div class="p-6 md:p-8">
    <h1 class="text-3xl font-bold text-base-content mb-6">Dashboard</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="card bg-base-100 shadow-sm border border-base-300">
            <div class="card-body">
                <h2 class="card-title text-lg">Total Balance</h2>
                <p class="text-3xl font-bold text-primary">$12,450.50</p>
                <p class="text-sm opacity-70">+2.5% from last month</p>
            </div>
        </div>

        <div class="card bg-base-100 shadow-sm border border-base-300">
            <div class="card-body">
                <h2 class="card-title text-lg">Income</h2>
                <p class="text-3xl font-bold text-success">$5,200</p>
                <p class="text-sm opacity-70">This month</p>
            </div>
        </div>

        <div class="card bg-base-100 shadow-sm border border-base-300">
            <div class="card-body">
                <h2 class="card-title text-lg">Expenses</h2>
                <p class="text-3xl font-bold text-error">$2,100</p>
                <p class="text-sm opacity-70">This month</p>
            </div>
        </div>

        <div class="card bg-base-100 shadow-sm border border-base-300">
            <div class="card-body">
                <h2 class="card-title text-lg">Transactions</h2>
                <p class="text-3xl font-bold text-info">248</p>
                <p class="text-sm opacity-70">Total count</p>
            </div>
        </div>
    </div>
</div>
@endsection