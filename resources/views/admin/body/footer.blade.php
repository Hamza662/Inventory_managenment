<footer class="content-footer footer bg-footer-theme">
    <div class="container-xxl d-flex flex-wrap justify-content-between py-3 flex-md-row flex-column">
        <div class="mb-2 mb-md-0">
            © <span id="xf-year"></span> Inventory Portal. Built for faster stock control.
        </div>
        <div>
            <a href="{{ route('admin.index') }}" class="footer-link me-4">Dashboard</a>
            <a href="{{ route('stocks.report') }}" class="footer-link me-4">Stock</a>
            <a href="{{ route('invoices.index') }}" class="footer-link">Invoices</a>
        </div>
    </div>
    <script>
        document.getElementById('xf-year').textContent = new Date().getFullYear();
    </script>
</footer>
