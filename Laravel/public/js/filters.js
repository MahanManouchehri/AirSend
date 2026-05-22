// ==================== مدیریت باز و بسته شدن فیلترها ====================
function toggleFilterSection() {
    const filterBody = document.getElementById('filterBody');
    const toggleIcon = document.getElementById('filterToggleIcon');
    
    if (!filterBody || !toggleIcon) return;
    
    if (filterBody.style.display === 'none' || filterBody.style.display === '') {
        filterBody.style.display = 'block';
        toggleIcon.style.transform = 'rotate(180deg)';
        // ذخیره وضعیت در localStorage با کلید مناسب بر اساس صفحه
        const pageKey = window.location.pathname.includes('employees') ? 'employeesFilterSectionOpen' : 'jobsFilterSectionOpen';
        localStorage.setItem(pageKey, 'true');
    } else {
        filterBody.style.display = 'none';
        toggleIcon.style.transform = 'rotate(0deg)';
        // ذخیره وضعیت در localStorage با کلید مناسب بر اساس صفحه
        const pageKey = window.location.pathname.includes('employees') ? 'employeesFilterSectionOpen' : 'jobsFilterSectionOpen';
        localStorage.setItem(pageKey, 'false');
    }
}

// بارگذاری وضعیت ذخیره شده فیلترها
function initFilterSection() {
    const filterBody = document.getElementById('filterBody');
    const toggleIcon = document.getElementById('filterToggleIcon');
    
    if (!filterBody || !toggleIcon) return;
    
    // تشخیص نوع صفحه برای استفاده از کلید مناسب در localStorage
    const isEmployeesPage = window.location.pathname.includes('employees');
    const pageKey = isEmployeesPage ? 'employeesFilterSectionOpen' : 'jobsFilterSectionOpen';
    
    // بررسی وجود فیلترهای فعال (این مقادیر باید از طریق متغیرهای Django منتقل شوند)
    const hasActiveFilters = typeof hasActiveFiltersVar !== 'undefined' ? hasActiveFiltersVar : false;
    
    // اگر فیلتر فعال وجود دارد، بخش فیلترها را باز نگه دار
    if (hasActiveFilters) {
        filterBody.style.display = 'block';
        toggleIcon.style.transform = 'rotate(180deg)';
        localStorage.setItem(pageKey, 'true');
    } else {
        // بررسی وضعیت ذخیره شده در localStorage
        const savedState = localStorage.getItem(pageKey);
        
        if (savedState === 'true') {
            filterBody.style.display = 'block';
            toggleIcon.style.transform = 'rotate(180deg)';
        } else if (savedState === 'false') {
            filterBody.style.display = 'none';
            toggleIcon.style.transform = 'rotate(0deg)';
        } else {
            // پیش‌فرض: بسته باشد
            filterBody.style.display = 'none';
            toggleIcon.style.transform = 'rotate(0deg)';
        }
    }
}

// تغییر تعداد نمایش در هر صفحه
function initPageSizeSelector() {
    const pageSizeSelect = document.getElementById('pageSizeSelect');
    if (pageSizeSelect) {
        pageSizeSelect.addEventListener('change', function() {
            const url = new URL(window.location.href);
            url.searchParams.set('page_size', this.value);
            url.searchParams.set('page', 1);
            window.location.href = url.toString();
        });
    }
}

// راه‌اندازی اولیه وقتی صفحه بارگذاری شد
document.addEventListener('DOMContentLoaded', function() {
    initFilterSection();
    initPageSizeSelector();
});