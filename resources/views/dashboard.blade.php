@extends('layouts.app')

@section('title', 'Dashboard - EPerpus Sawit')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-md">
        <div class="container mx-auto px-6 py-4">
            <div class="flex justify-between items-center">
                <div class="text-2xl font-bold text-blue-600">EPerpus Sawit</div>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-700">{{ auth()->user()->name }}</span>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-red-600 hover:text-red-800 font-medium">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar & Content -->
    <div class="flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-md min-h-screen">
            <ul class="space-y-2 p-6">
                <li>
                    <a href="{{ route('dashboard') }}" class="block px-4 py-2 rounded-lg bg-blue-600 text-white font-medium">
                        📊 Dashboard
                    </a>
                </li>
                <li>
                    <a href="javascript:void(0);" onclick="alert('Kelola Buku tersedia melalui API: /api/books')" class="block px-4 py-2 rounded-lg text-gray-700 hover:bg-gray-100 cursor-pointer">
                        📚 Kelola Buku (API)
                    </a>
                </li>
                <li>
                    <a href="{{ route('members.index') }}" class="block px-4 py-2 rounded-lg text-gray-700 hover:bg-gray-100">
                        👥 Kelola Anggota
                    </a>
                </li>
                <li>
                    <a href="{{ route('borrowings.index') }}" class="block px-4 py-2 rounded-lg text-gray-700 hover:bg-gray-100">
                        📤 Peminjaman
                    </a>
                </li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-8">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-4 gap-6 mb-8">
                <!-- Total Books -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="text-gray-600 text-sm font-medium">Total Buku</div>
                    <div class="text-3xl font-bold text-blue-600 mt-2">{{ $totalBooks }}</div>
                </div>

                <!-- Total Members -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="text-gray-600 text-sm font-medium">Total Anggota</div>
                    <div class="text-3xl font-bold text-green-600 mt-2">{{ $totalMembers }}</div>
                </div>

                <!-- Active Borrowings -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="text-gray-600 text-sm font-medium">Peminjaman Aktif</div>
                    <div class="text-3xl font-bold text-yellow-600 mt-2">{{ $activeBorrowings }}</div>
                </div>

                <!-- Total Fine -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="text-gray-600 text-sm font-medium">Total Denda</div>
                    <div class="text-3xl font-bold text-red-600 mt-2">Rp {{ number_format($totalFine, 0, ',', '.') }}</div>
                </div>
            </div>

            <!-- Alerts -->
            @if(count($overdueBorrowings) > 0)
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-8">
                    <h3 class="text-red-800 font-semibold mb-2">⚠️ {{ count($overdueBorrowings) }} Peminjaman Terlambat</h3>
                    <ul class="text-red-700 text-sm">
                        @foreach($overdueBorrowings as $borrowing)
                            <li>{{ $borrowing->member->name }} - {{ $borrowing->books->first()->title ?? 'N/A' }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(count($lowStockBooks) > 0)
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-8">
                    <h3 class="text-yellow-800 font-semibold mb-2">📌 {{ count($lowStockBooks) }} Buku Stok Rendah</h3>
                    <ul class="text-yellow-700 text-sm">
                        @foreach($lowStockBooks as $book)
                            <li>{{ $book->title }} - Stok: {{ $book->quantity }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Recent Borrowings -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">📖 Peminjaman Terbaru</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-100 border-b">
                            <tr>
                                <th class="px-4 py-2">Anggota</th>
                                <th class="px-4 py-2">Buku</th>
                                <th class="px-4 py-2">Tanggal Pinjam</th>
                                <th class="px-4 py-2">Jatuh Tempo</th>
                                <th class="px-4 py-2">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentBorrowings as $borrowing)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-2">{{ $borrowing->member->name }}</td>
                                    <td class="px-4 py-2">{{ $borrowing->books->first()->title ?? 'N/A' }}</td>
                                    <td class="px-4 py-2">{{ $borrowing->borrow_date->format('d M Y') }}</td>
                                    <td class="px-4 py-2">{{ $borrowing->return_date->format('d M Y') }}</td>
                                    <td class="px-4 py-2">
                                        @if($borrowing->status === 'borrowed')
                                            <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm">Dipinjam</span>
                                        @else
                                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm">Dikembalikan</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-4 text-center text-gray-600">Belum ada data peminjaman</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection
