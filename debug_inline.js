
        function toggleMobileSidebar() {
            const sidebar = document.getElementById('sidebar-menu');
            const backdrop = document.getElementById('mobile-sidebar-backdrop');
            if (sidebar && backdrop) {
                sidebar.classList.toggle('-translate-x-full');
                backdrop.classList.toggle('hidden');
            }
        }

        function closeMobileSidebar() {
            const sidebar = document.getElementById('sidebar-menu');
            const backdrop = document.getElementById('mobile-sidebar-backdrop');
            if (sidebar && backdrop) {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
            }
        }

        function toggleUserMobileMenu() {
            const menu = document.getElementById('user-mobile-menu');
            if (menu) {
                menu.classList.toggle('hidden');
            }
        }

        function switchTabAdmin(tabId, btnEl) {
            closeMobileSidebar();
            document.querySelectorAll('.tab-content').forEach(c => c.classList.add('hidden'));
            const target = document.getElementById(tabId);
            if (target) target.classList.remove('hidden');

            document.querySelectorAll('.tab-btn-admin').forEach(b => {
                b.classList.remove('bg-red-50', 'text-[#c52228]');
                b.classList.add('hover:bg-gray-50', 'text-gray-600');
            });
            if (btnEl) {
                btnEl.classList.remove('hover:bg-gray-50', 'text-gray-600');
                btnEl.classList.add('bg-red-50', 'text-[#c52228]');
            }
        }

        // SMOOTH ANIMATED ACTIVE SLIDING UNDERLINE INDICATOR FOR NAVBAR
        function moveNavIndicator(activeEl) {
            const indicator = document.getElementById('nav-active-indicator');
            if (indicator && activeEl) {
                indicator.style.left = activeEl.offsetLeft + 'px';
                indicator.style.width = activeEl.offsetWidth + 'px';
                indicator.style.opacity = '1';
            } else if (indicator) {
                indicator.style.opacity = '0';
            }
        }

        function switchTabTop(tabId) {
            // 1. Hide all sections
            document.querySelectorAll('.tab-content-top').forEach(c => c.classList.add('hidden'));

            // 2. Show target section
            const target = document.getElementById(tabId);
            if (target) target.classList.remove('hidden');

            // 3. Update active nav button & slide indicator
            document.querySelectorAll('.nav-tab-item').forEach(btn => {
                btn.classList.remove('text-gray-900', 'font-bold');
                btn.classList.add('text-gray-500', 'font-semibold');
            });

            const activeBtn = document.getElementById('nav-btn-' + tabId);
            if (activeBtn) {
                activeBtn.classList.add('text-gray-900', 'font-bold');
                activeBtn.classList.remove('text-gray-500', 'font-semibold');
                moveNavIndicator(activeBtn);
            }

            if (window.history && window.history.pushState) {
                window.history.pushState(null, null, '#' + tabId);
            }
        }

        function filterCatalogProducts() {
            const query = (document.getElementById('catalog-search-input').value || '').toLowerCase();
            document.querySelectorAll('.product-item-card').forEach(card => {
                const title = card.getAttribute('data-title') || '';
                if (title.includes(query)) {
                    card.classList.remove('hidden');
                } else {
                    card.classList.add('hidden');
                }
            });
        }

        function openDetailModal(idProduct, title, price, stock, desc, category) {
            document.getElementById('modal-title').innerText = title;
            document.getElementById('modal-price').innerText = 'Rp ' + price;
            document.getElementById('modal-stock').innerText = stock + ' pcs';
            document.getElementById('modal-desc').innerText = desc || 'Tidak ada deskripsi.';
            document.getElementById('modal-category').innerText = category;

            const buyBtn = document.getElementById('modal-buy-btn');
            if (buyBtn) {
                const stockInt = parseInt(stock) || 0;
                if (stockInt > 0) {
                    buyBtn.disabled = false;
                    buyBtn.innerText = '+ Beli';
                    buyBtn.className = 'w-1/2 bg-[#c52228] text-white font-bold py-2 rounded-xl text-xs md:text-sm shadow-xs cursor-pointer hover:bg-[#a51c21]';
                    buyBtn.onclick = function() {
                        addItemToCart(idProduct);
                        closeDetailModal();
                    };
                } else {
                    buyBtn.disabled = true;
                    buyBtn.innerText = 'Stok Habis';
                    buyBtn.className = 'w-1/2 bg-gray-200 text-gray-400 font-bold py-2 rounded-xl text-xs md:text-sm cursor-not-allowed';
                    buyBtn.onclick = null;
                }
            }

            const modal = document.getElementById('detail-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeDetailModal() {
            const modal = document.getElementById('detail-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function openCheckoutModal() {
            const modal = document.getElementById('checkout-modal');
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        }
        
        function closeCheckoutModal() {
            const modal = document.getElementById('checkout-modal');
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }

        function openPaymentModal(trxId, totalFormatted) {
            const modal = document.getElementById('payment-modal');
            if (modal) {
                document.getElementById('payment-total-label').innerText = 'Rp ' + totalFormatted;
                document.getElementById('payment-jumlah').value = parseFloat(totalFormatted.replace(/[^0-9]/g, ''));
                const form = document.getElementById('payment-form');
                form.action = `/transaction/${trxId}/pay`;
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        }

        function closePaymentModal() {
            const modal = document.getElementById('payment-modal');
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }

        function openReviewModal(details) {
            const select = document.getElementById('review-product-select');
            select.innerHTML = '';
            details.forEach(d => {
                const opt = document.createElement('option');
                opt.value = d.id_transaction_detail;
                opt.innerText = d.nama_produk + (d.reviewed ? ' ✓ (sudah diulas)' : '');
                select.appendChild(opt);
            });
            setRating(0);
            document.getElementById('review-komentar').value = '';
            
            const modal = document.getElementById('review-modal');
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        }

        function closeReviewModal() {
            const modal = document.getElementById('review-modal');
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }

        function setRating(val) {
            document.getElementById('review-rating-value').value = val || '';
            const stars = document.querySelectorAll('.star-rating');
            stars.forEach((star, idx) => {
                if (idx < val) {
                    star.classList.remove('text-gray-300');
                    star.classList.add('text-yellow-400');
                } else {
                    star.classList.remove('text-yellow-400');
                    star.classList.add('text-gray-300');
                }
            });
        }

        function disableSubmitButton(form) {
            const btn = form.querySelector('button[type="submit"]');
            if (btn) {
                btn.disabled = true;
                btn.innerText = 'Memproses...';
                btn.classList.add('opacity-50', 'cursor-not-allowed');
            }
        }

        function addItemToCart(productId) {
            fetch('http://localhost/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': 'XZIjcMflFMF5pKzlCm5ZVOrKREmquARtPKhmB6a1'
                },
                body: JSON.stringify({ id_product: productId, quantity: 1 })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                    updateCartBadge(data.cart_count);
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    showToast(data.message || 'Gagal menambahkan barang.', 'error');
                }
            })
            .catch(err => {
                showToast('Terjadi kesalahan koneksi.', 'error');
            });
        }

        function incrementQty(productId, stock) {
            const input = document.getElementById('qty-input-' + productId);
            let currentVal = parseInt(input.value);
            if (currentVal >= stock) {
                showToast('Kuantitas melebihi stok yang tersedia!', 'error');
                return;
            }
            updateCartQuantity(productId, currentVal + 1);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const hash = window.location.hash.replace('#', '') || 'user-catalog';
            if (document.getElementById(hash)) {
                switchTabTop(hash);
            } else {
                switchTabTop('user-catalog');
            }
        });

        function decrementQty(productId) {
            const input = document.getElementById('qty-input-' + productId);
            let currentVal = parseInt(input.value);
            if (currentVal <= 1) {
                return;
            }
            updateCartQuantity(productId, currentVal - 1);
        }

        function updateCartQuantity(productId, newQty) {
            fetch('http://localhost/cart/update', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': 'XZIjcMflFMF5pKzlCm5ZVOrKREmquARtPKhmB6a1'
                },
                body: JSON.stringify({ id_product: productId, quantity: newQty })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('qty-input-' + productId).value = newQty;
                    document.getElementById('subtotal-' + productId).innerText = data.item_subtotal;
                    document.getElementById('cart-total-price').innerText = data.cart_total;
                    updateCartBadge(data.cart_count);
                    showToast(data.message, 'success');
                } else {
                    showToast(data.message || 'Gagal memperbarui kuantitas.', 'error');
                }
            })
            .catch(err => {
                showToast('Terjadi kesalahan koneksi.', 'error');
            });
        }

        function removeItemFromCart(productId) {
            if (!confirm('Apakah Anda yakin ingin menghapus barang ini dari keranjang?')) {
                return;
            }
            fetch('http://localhost/cart/remove', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': 'XZIjcMflFMF5pKzlCm5ZVOrKREmquARtPKhmB6a1'
                },
                body: JSON.stringify({ id_product: productId })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const row = document.getElementById('cart-item-' + productId);
                    if (row) {
                        row.remove();
                    }
                    document.getElementById('cart-total-price').innerText = data.cart_total;
                    updateCartBadge(data.cart_count);
                    showToast(data.message, 'success');
                    if (data.cart_count === 0) {
                        setTimeout(() => window.location.reload(), 800);
                    }
                } else {
                    showToast(data.message || 'Gagal menghapus barang.', 'error');
                }
            })
            .catch(err => {
                showToast('Terjadi kesalahan koneksi.', 'error');
            });
        }

        function updateCartBadge(count) {
            const badge = document.getElementById('cart-badge');
            if (badge) {
                badge.innerText = count;
                if (count > 0) {
                    badge.classList.remove('hidden');
                } else {
                    badge.classList.add('hidden');
                }
            }
        }

        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `fixed bottom-4 right-4 px-4 py-2.5 rounded-xl text-white text-xs font-bold shadow-lg transition-all transform translate-y-10 opacity-0 z-50 flex items-center space-x-2 ${type === 'success' ? 'bg-emerald-600' : 'bg-[#c52228]'}`;
            toast.innerHTML = `
                <span>${message}</span>
            `;
            document.body.appendChild(toast);
            setTimeout(() => {
                toast.classList.remove('translate-y-10', 'opacity-0');
            }, 50);
            setTimeout(() => {
                toast.classList.add('translate-y-10', 'opacity-0');
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        window.addEventListener('resize', function() {
            const activeBtn = document.querySelector('.nav-tab-item.text-gray-900');
            if (activeBtn) {
                moveNavIndicator(activeBtn);
            }
        });

        function previewProfileImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.getElementById('avatar-preview-img');
                    const fallback = document.getElementById('avatar-fallback-initial');
                    if (img && fallback) {
                        img.src = e.target.result;
                        img.classList.remove('hidden');
                        fallback.classList.add('hidden');
                    }
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function cancelProfileChanges() {
            const form = document.getElementById('profile-form');
            const input = document.getElementById('foto');
            const img = document.getElementById('avatar-preview-img');
            const fallback = document.getElementById('avatar-fallback-initial');

            if (form) {
                form.reset();
            }
            if (input) {
                input.value = '';
            }

            if (img && fallback) {
                const originalSrc = img.getAttribute('data-original-src');
                if (originalSrc) {
                    img.src = originalSrc;
                    img.classList.remove('hidden');
                    fallback.classList.add('hidden');
                } else {
                    img.src = '';
                    img.classList.add('hidden');
                    fallback.classList.remove('hidden');
                }
            }
        }
    