<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faktur Pesanan #{{ $order->order_number }}</title>
    <!-- Tailwind CSS (Opsional untuk tampilan layar, tapi diabaikan saat print) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        /* CSS umum untuk tampilan struk */
        body { 
            font-family: 'Inter', sans-serif; 
            margin: 0;
            padding: 0;
            color: #333;
            background-color: #f7f7f7; /* Latar belakang abu-abu hanya untuk layar */
        }
        .invoice-container {
            width: 80mm; /* Lebar standar untuk thermal receipt (80mm) */
            margin: 20px auto;
            padding: 15px;
            background-color: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1); /* Hanya terlihat di layar */
            font-size: 10px;
        }

        /* CSS KHUSUS UNTUK CETAK */
        @media print {
            .no-print {
                display: none !important; /* Sembunyikan tombol/elemen yang tidak perlu dicetak */
            }
            .invoice-container {
                width: 100%; /* Gunakan lebar penuh kertas saat dicetak */
                margin: 0;
                padding: 0;
                box-shadow: none;
            }
            body {
                font-size: 10pt; /* Atur ulang ukuran font untuk cetak */
                background-color: white;
            }
            .items td {
                padding: 2px 0; /* Kurangi padding saat cetak */
            }
        }
        
        .header, .footer { text-align: center; margin-bottom: 10px; }
        .details, .items { margin-bottom: 10px; }
        .items table { width: 100%; border-collapse: collapse; }
        .items th, .items td { padding: 4px 0; }
        .items tr:not(:last-child) td { border-bottom: 1px dashed #e0e0e0; }
        .items th { text-align: left; font-weight: 700; }
        .items td:nth-child(2) { text-align: center; }
        .items td:nth-child(3) { text-align: right; }
    </style>
</head>
<body class="print-preview" onload="window.print()">
    <div class="invoice-container">
        
        <!-- Header Toko -->
        <div class="header">
            <h1 class="text-lg font-extrabold text-gray-900">Cafe</h1>
            <p class="text-xs">Jl. Contoh Digital No. 99, Jakarta</p>
            <p class="text-xs">Telp: 085128329385 | Email: Cafe@gmail.com</p>
            <hr class="my-3 border-t-2 border-dashed border-gray-600">
        </div>

        <!-- Detail Pesanan -->
        <div class="details text-xs leading-relaxed">
            <p class="font-bold mb-1 text-base text-center">FAKTUR PENJUALAN</p>
            <table class="w-full">
                <tr>
                    <td class="w-1/3">No. Order</td>
                    <td class="w-2/3 font-semibold">: {{ $order->order_number }}</td>
                </tr>
                <tr>
                    <td>Tanggal</td>
                    <td class="font-semibold">: {{ $order->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                <tr>
                    <td>Pelanggan</td>
                    <td class="font-semibold">: {{ $order->customer_name }} ({{ $order->customer_phone }})</td>
                </tr>
            </table>
            <hr class="my-3 border-dashed border-gray-400">
            <div class="text-center">
                <p class="text-sm font-bold">NO. ANTRIAN</p>
                <p class="text-5xl font-extrabold text-red-700">{{ $order->queue_number }}</p>
            </div>
            <hr class="my-3 border-dashed border-gray-400">
        </div>

        <!-- Item Pesanan -->
        <div class="items">
            <table>
                <thead>
                    <tr>
                        <th class="text-left">Item</th>
                        <th class="text-center">Qty</th>
                        <th class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->orderItems as $item)
                        <tr class="text-xs">
                            <td>
                                {{ $item->menu->name ?? 'Menu Dihapus' }}
                                @if ($item->notes)
                                    <br><span class="text-gray-600 italic text-[9px]">({{ $item->notes }})</span>
                                @endif
                            </td>
                            <td class="text-center font-bold">{{ $item->quantity }}</td>
                            <td class="text-right">Rp{{ number_format($item->price, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <hr class="my-3 border-t-2 border-solid border-gray-900">
        </div>

        <!-- Total Pembayaran -->
        <div class="totals text-right text-sm">
            <div class="flex justify-between">
                <span class="font-semibold">Subtotal:</span>
                <span>Rp{{ number_format($order->total_amount, 0, ',', '.') }}</span>
            </div>
            
            <div class="flex justify-between font-bold text-xl mt-3">
                <span>TOTAL:</span>
                <span>Rp{{ number_format($order->total_amount, 0, ',', '.') }}</span>
            </div>
            <hr class="my-3 border-dashed border-gray-400">
        </div>
        
        <!-- Catatan & Footer -->
        <div class="footer text-xs">
            @if ($order->notes)
                <p class="text-left mb-2 italic">Catatan: {{ $order->notes }}</p>
            @endif
            <p class="mb-1 font-semibold text-sm">Terima kasih atas kunjungan Anda!</p>
            <p class="italic text-[9px]">Mohon periksa pesanan Anda sebelum meninggalkan area kasir.</p>
        </div>
    </div>

    <!-- Tombol untuk kembali dan cetak (Disembunyikan saat dicetak) -->
    <div class="no-print fixed bottom-0 left-0 w-full p-4 bg-white border-t shadow-lg flex justify-center space-x-4">
        <a href="{{ route('admin.orders.index') }}" class="px-6 py-2 bg-gray-500 text-white rounded-lg shadow-md hover:bg-gray-600 transition flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
        <button onclick="window.print()" class="px-6 py-2 bg-sky-600 text-white rounded-lg shadow-md hover:bg-sky-700 transition flex items-center">
            <i class="fas fa-print mr-2"></i> Cetak Ulang
        </button>
    </div>
    
</body>
</html>