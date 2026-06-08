<?= $this->extend('layouts/main') ?>

<?php
/**
 * @var array $products
 * @var array $departments
 * @var string $title
 */
?>

<?= $this->section('content') ?>
<div class="max-w-lg mx-auto">
    <div class="mb-6"><a href="/staff/requests" class="inline-flex items-center gap-1 text-sm text-primary-600 hover:text-primary-700 font-medium transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>Back to My Requests</a></div>
    <div class="card">
        <div class="card-header bg-gradient-to-r from-gray-50 to-white"><h3 class="text-lg font-bold">New Supply Request</h3></div>
        <div class="card-body">
            <form action="/staff/requests/store" method="POST" class="space-y-5">
                <?= csrf_field() ?>
                <div>
                    <label class="form-label">Product *</label>
                    <div x-data="{
                        open: false,
                        search: '',
                        selectedCategory: 'All',
                        selectedId: '<?= old('product_id', '') ?>',
                        selectedLabel: 'Select a product',
                        options: [
                            <?php foreach ($products as $p): 
                                $catName = esc($p['category_name'] ?? 'Uncategorized');
                                $label = esc($p['code']) . ' — ' . esc($p['name']);
                            ?>
                            { 
                                id: '<?= $p['id'] ?>', 
                                label: '<?= addslashes($label) ?>', 
                                name: '<?= addslashes(esc($p['name'])) ?>', 
                                code: '<?= addslashes(esc($p['code'])) ?>',
                                category: '<?= addslashes($catName) ?>',
                                stock: '<?= $p['quantity_in_stock'] ?>'
                            },
                            <?php endforeach; ?>
                        ],
                        categories: [],
                        init() {
                            const cats = new Set(this.options.map(opt => opt.category));
                            this.categories = ['All', ...cats];

                            const selected = this.options.find(opt => opt.id == this.selectedId);
                            if (selected) {
                                this.selectedLabel = selected.code + ' — ' + selected.name;
                            }
                        },
                        get filteredOptions() {
                            return this.options.filter(opt => {
                                if (this.selectedCategory !== 'All' && opt.category !== this.selectedCategory) {
                                    return false;
                                }
                                if (!this.search) return true;
                                const query = this.search.toLowerCase();
                                return opt.name.toLowerCase().includes(query) || 
                                       opt.code.toLowerCase().includes(query) ||
                                       opt.category.toLowerCase().includes(query);
                            });
                        },
                        select(opt) {
                            this.selectedId = opt.id;
                            this.selectedLabel = opt.code + ' — ' + opt.name;
                            this.open = false;
                            this.search = '';
                        }
                    }" 
                    class="relative"
                    @click.outside="open = false">
                        <!-- Hidden input to submit the actual value -->
                        <input type="hidden" name="product_id" :value="selectedId" required>

                        <!-- Trigger Button -->
                        <button type="button" 
                                @click="open = !open" 
                                class="form-select w-full text-left flex items-center justify-between cursor-pointer"
                                :class="open ? 'border-primary-500 ring-4 ring-primary-500/10' : ''">
                            <span x-text="selectedLabel" class="truncate text-sm text-gray-700">Select a product</span>
                        </button>

                        <!-- Dropdown List -->
                        <div x-show="open" 
                             x-cloak
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute z-30 w-full mt-2 bg-white rounded-xl shadow-2xl border border-gray-100 overflow-hidden max-h-72 flex flex-col">
                             
                            <!-- Search Input -->
                            <div class="p-2 border-b border-gray-100 bg-gray-50 shrink-0 flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400 shrink-0 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                <input type="text" 
                                       x-model="search" 
                                       @keydown.escape="open = false"
                                       class="w-full text-xs border-0 bg-transparent focus:ring-0 focus:outline-none p-1 text-gray-700" 
                                       placeholder="Type to search name, code, or category...">
                            </div>

                            <!-- Category Dropdown Filter -->
                            <div class="px-2 py-1.5 bg-gray-50 border-b border-gray-100 shrink-0">
                                <select x-model="selectedCategory" 
                                        class="w-full text-[11px] bg-white border border-gray-200 rounded-lg py-1 px-2 text-gray-600 focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500 cursor-pointer font-semibold">
                                    <template x-for="cat in categories" :key="cat">
                                        <option :value="cat" x-text="cat === 'All' ? '🗂️ All Categories' : '📁 ' + cat"></option>
                                    </template>
                                </select>
                            </div>

                            <!-- Options Container -->
                            <div class="overflow-y-auto flex-1 divide-y divide-gray-50 max-h-48 custom-scrollbar">
                                <template x-for="opt in filteredOptions" :key="opt.id">
                                    <button type="button" 
                                            @click="select(opt)"
                                            class="w-full text-left px-4 py-2 text-xs text-gray-700 hover:bg-primary-50 hover:text-primary-700 transition-colors flex items-center justify-between"
                                            :class="selectedId == opt.id ? 'bg-primary-50/50 text-primary-700 font-semibold' : ''">
                                        <div class="flex flex-col gap-0.5 min-w-0 text-left">
                                            <div class="flex items-center gap-1.5">
                                                <span class="badge badge-gray text-[8px] px-1 py-0 shrink-0 font-bold" x-text="opt.category"></span>
                                                <span class="font-mono text-[9px] text-gray-400" x-text="opt.code"></span>
                                            </div>
                                            <div class="text-xs text-gray-700 mt-0.5">
                                                <span x-text="opt.name" class="font-semibold text-sm"></span>
                                                <span class="text-gray-400 text-[10px] ml-1" x-text="'(' + opt.stock + ' in stock)'"></span>
                                            </div>
                                        </div>
                                        <span x-show="selectedId == opt.id" class="text-primary-600 shrink-0 ml-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                        </span>
                                    </button>
                                </template>
                                <div x-show="filteredOptions.length === 0" class="p-4 text-center text-xs text-gray-400">
                                    No matching products found
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div><label class="form-label">Quantity *</label><input type="number" name="requested_quantity" value="<?= old('requested_quantity', 1) ?>" class="form-input" min="1" required></div>
                <div><label class="form-label">Notes / Justification</label><textarea name="notes" class="form-input" rows="3" placeholder="Explain why you need these items..."><?= old('notes') ?></textarea></div>
                <div class="flex gap-3"><button type="submit" class="btn-primary">Submit Request</button><a href="/staff/requests" class="btn-secondary">Cancel</a></div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
