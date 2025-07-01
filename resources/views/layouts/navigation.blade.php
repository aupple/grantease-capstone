<nav x-data="{ open: false }" class="m-0 p-0 bg-transparent shadow-none">
    <!-- Primary Navigation Menu -->
    <div class="m-0 p-0">
        <div class="flex justify-between items-center h-0">
            <!-- Removed nav content for now -->
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <!-- No mobile menu content -->
    </div>
</nav>
