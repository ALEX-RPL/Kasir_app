@extends('layouts.app')

@section('title', 'Transaksi Baru')

@section('styles')
<style>
    .product-item {
        background-color: rgba(209, 190, 156, 0.2);
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 15px;
        border-left: 4px solid var(--primary-color);
    }
    
    .remove-product {
        cursor: pointer;
        color: var(--danger-color);
    }
    
    .discount-badge {
        background-color: var(--accent-color);
    }
</style>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-cart-plus me-2"></i>Transaksi Baru</h1>
    <a href="{{ route('transactions.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-shopping-cart me-1"></i> Informasi Transaksi
            </div>
            <div class="card-body">
                <form id="transactionForm" action="{{ route('transactions.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="invoice_number" class="form-label">Nomor Invoice</label>
                        <input type="text" class="form-control" id="invoice_number" value="{{ $invoiceNumber }}" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label for="customer_name" class="form-label">Nama Customer (Opsional)</label>
                        <input type="text" class="form-control" id="customer_name" name="customer_name" value="{{ old('customer_name') }}">
                    </div>
                    
                    <div class="mb-3">
                        <label for="transaction_date" class="form-label">Tanggal Transaksi</label>
                        <input type="text" class="form-control" id="transaction_date" value="{{ date('d/m/Y H:i') }}" readonly>
                    </div>
                    
                    <hr>
                    
                    <h5>Daftar Barang</h5>
                    <div id="productList"></div>
                    
                    <div class="alert alert-info" id="emptyCart">
                        <i class="fas fa-info-circle me-1"></i> Belum ada barang yang ditambahkan
                    </div>
                    
                    <div class="text-center mt-4">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
                            <i class="fas fa-plus me-1"></i> Tambah Barang
                        </button>
                    </div>

                    <hr>
                    
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Total Item</label>
                                <input type="text" class="form-control" id="totalItems" value="0" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Total Harga</label>
                                <input type="text" class="form-control" id="totalPrice" value="Rp 0" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Total Diskon</label>
                                <input type="text" class="form-control" id="totalDiscount" value="Rp 0" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Harga Akhir</label>
                                <input type="text" class="form-control" id="finalPrice" value="Rp 0" readonly>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-success" id="saveTransaction" disabled>
                            <i class="fas fa-save me-1"></i> Simpan Transaksi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-info-circle me-1"></i> Informasi
            </div>
            <div class="card-body">
                <p>Halaman ini digunakan untuk membuat transaksi baru. Silakan tambahkan barang yang dibeli oleh customer dengan menekan tombol "Tambah Barang".</p>
                <p>Pastikan jumlah barang yang dimasukkan sesuai dengan pembelian customer.</p>
                <hr>
                <h6>Keuntungan Customer</h6>
                <p>Total potongan harga yang didapatkan oleh customer dari pembelian ini:</p>
                <h4 id="customerBenefit" class="text-success">Rp 0</h4>
            </div>
        </div>
    </div>
</div>

<!-- Modal Add Product -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background-color: var(--secondary-color);">
                <h5 class="modal-title" id="addProductModalLabel">
                    <i class="fas fa-plus-circle me-1"></i> Tambah Barang
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="product_id" class="form-label">Pilih Produk</label>
                    <select class="form-select" id="product_id" required>
                        <option value="" selected disabled>-- Pilih Produk --</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" data-price="{{ $product->price }}"
                                    data-discount="{{ $product->discount_percentage }}"
                                    data-price-discount="{{ $product->price_after_discount }}"
                                    data-stock="{{ $product->stock }}">
                                {{ $product->name }} - Stok: {{ $product->stock }}
                            </option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback" id="product-error"></div>
                </div>
                
                <div class="mb-3">
                    <label for="quantity" class="form-label">Jumlah</label>
                    <input type="number" class="form-control" id="quantity" min="1" value="1" required>
                    <div class="invalid-feedback" id="quantity-error"></div>
                </div>
                
                <div class="product-details d-none" id="productDetails">
                    <hr>
                    <h6>Detail Produk</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Harga Satuan</label>
                                <input type="text" class="form-control" id="productPrice" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Diskon</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="productDiscount" readonly>
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Harga Setelah Diskon</label>
                                <input type="text" class="form-control" id="productPriceAfterDiscount" readonly>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Subtotal</label>
                                <input type="text" class="form-control" id="productSubtotal" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="addProductButton" disabled>
                    <i class="fas fa-plus me-1"></i> Tambah
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Product data cache
        const products = {};
        
        // Cart
        let cart = [];
        
        // Format currency
        function formatCurrency(amount) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
        }
        
        // Product selection
        $('#product_id').change(function() {
            const productId = $(this).val();
            if (!productId) return;
            
            const option = $(this).find('option:selected');
            const price = parseFloat(option.data('price'));
            const discount = parseFloat(option.data('discount'));
            const priceAfterDiscount = parseFloat(option.data('price-discount'));
            const stock = parseInt(option.data('stock'));
            
            // Store product data
            products[productId] = {
                id: productId,
                name: option.text().split(' - Stok:')[0],
                price: price,
                discount_percentage: discount,
                price_after_discount: priceAfterDiscount,
                stock: stock
            };
            
            // Show product details
            $('#productPrice').val(formatCurrency(price));
            $('#productDiscount').val(discount);
            $('#productPriceAfterDiscount').val(formatCurrency(priceAfterDiscount));
            
            updateSubtotal();
            $('#productDetails').removeClass('d-none');
            validateForm();
        });
        
        // Update subtotal when quantity changes
        $('#quantity').on('input', function() {
            updateSubtotal();
            validateForm();
        });
        
        function updateSubtotal() {
            const productId = $('#product_id').val();
            if (!productId) return;
            
            const quantity = parseInt($('#quantity').val()) || 0;
            const product = products[productId];
            
            if (product) {
                const subtotal = product.price_after_discount * quantity;
                $('#productSubtotal').val(formatCurrency(subtotal));
            }
        }
        
        function validateForm() {
            const productId = $('#product_id').val();
            const quantity = parseInt($('#quantity').val()) || 0;
            
            let isValid = true;
            
            // Reset errors
            $('#product-error').text('');
            $('#quantity-error').text('');
            $('#product_id').removeClass('is-invalid');
            $('#quantity').removeClass('is-invalid');
            
            // Validate product selection
            if (!productId) {
                $('#product_id').addClass('is-invalid');
                $('#product-error').text('Pilih produk terlebih dahulu');
                isValid = false;
            } else {
                const product = products[productId];
                
                // Validate quantity
                if (quantity <= 0) {
                    $('#quantity').addClass('is-invalid');
                    $('#quantity-error').text('Jumlah harus lebih dari 0');
                    isValid = false;
                } else if (quantity > product.stock) {
                    $('#quantity').addClass('is-invalid');
                    $('#quantity-error').text(`Stok tidak mencukupi (tersedia: ${product.stock})`);
                    isValid = false;
                }
            }
            
            // Enable/disable add button
            $('#addProductButton').prop('disabled', !isValid);
            
            return isValid;
        }
        
        // Add product to cart
        $('#addProductButton').click(function() {
            if (!validateForm()) return;
            
            const productId = $('#product_id').val();
            const product = products[productId];
            const quantity = parseInt($('#quantity').val());
            
            // Check if product already in cart
            const existingProductIndex = cart.findIndex(item => item.id === productId);
            
            if (existingProductIndex !== -1) {
                // Update quantity if product already in cart
                const newQuantity = cart[existingProductIndex].quantity + quantity;
                
                // Check if new quantity exceeds stock
                if (newQuantity > product.stock) {
                    alert(`Total jumlah melebihi stok yang tersedia (${product.stock})`);
                    return;
                }
                
                cart[existingProductIndex].quantity = newQuantity;
                cart[existingProductIndex].subtotal = product.price_after_discount * newQuantity;
            } else {
                // Add new product to cart
                cart.push({
                    id: productId,
                    name: product.name,
                    price: product.price,
                    discount_percentage: product.discount_percentage,
                    discount_amount: product.price - product.price_after_discount,
                    price_after_discount: product.price_after_discount,
                    quantity: quantity,
                    subtotal: product.price_after_discount * quantity
                });
            }
            
            // Update UI
            updateCartUI();
            
            // Reset form
            $('#product_id').val('');
            $('#quantity').val(1);
            $('#productDetails').addClass('d-none');
            $('#addProductButton').prop('disabled', true);
            
            // Close modal
            $('#addProductModal').modal('hide');
        });
        
        // Remove product from cart
        $(document).on('click', '.remove-product', function() {
            const index = $(this).data('index');
            cart.splice(index, 1);
            updateCartUI();
        });
        
        // Update cart UI
        function updateCartUI() {
            if (cart.length === 0) {
                $('#productList').html('');
                $('#emptyCart').show();
                $('#saveTransaction').prop('disabled', true);
                
                // Reset totals
                $('#totalItems').val(0);
                $('#totalPrice').val(formatCurrency(0));
                $('#totalDiscount').val(formatCurrency(0));
                $('#finalPrice').val(formatCurrency(0));
                $('#customerBenefit').text(formatCurrency(0));
                
                return;
            }
            
            let html = '';
            let totalItems = 0;
            let totalPrice = 0;
            let totalDiscount = 0;
            let finalPrice = 0;
            
            cart.forEach((item, index) => {
                totalItems += item.quantity;
                totalPrice += item.price * item.quantity;
                totalDiscount += (item.price - item.price_after_discount) * item.quantity;
                finalPrice += item.subtotal;
                
                html += `
                    <div class="product-item">
                        <div class="d-flex justify-content-between">
                            <h6>${item.name}</h6>
                            <div>
                                <span class="badge bg-secondary me-2">${item.quantity}x</span>
                                <span class="badge discount-badge">${item.discount_percentage}% OFF</span>
                                <i class="fas fa-times remove-product ms-2" data-index="${index}"></i>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-4">
                                <small>Harga: ${formatCurrency(item.price)}</small>
                            </div>
                            <div class="col-md-4">
                                <small>Diskon: ${formatCurrency(item.discount_amount)}</small>
                            </div>
                            <div class="col-md-4">
                                <small>Subtotal: ${formatCurrency(item.subtotal)}</small>
                            </div>
                        </div>
                        <input type="hidden" name="product_ids[]" value="${item.id}">
                        <input type="hidden" name="quantities[]" value="${item.quantity}">
                    </div>
                `;
            });
            
            $('#productList').html(html);
            $('#emptyCart').hide();
            $('#saveTransaction').prop('disabled', false);
            
            // Update totals
            $('#totalItems').val(totalItems);
            $('#totalPrice').val(formatCurrency(totalPrice));
            $('#totalDiscount').val(formatCurrency(totalDiscount));
            $('#finalPrice').val(formatCurrency(finalPrice));
            $('#customerBenefit').text(formatCurrency(totalDiscount));
        }
    });
</script>
@endsection