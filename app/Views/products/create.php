<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="max-w-2xl mx-auto">
    <div class="mb-6"><a href="/products" class="inline-flex items-center gap-1 text-sm text-primary-600 hover:text-primary-700 font-medium transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>Back to Products</a></div>
    <div class="card">
        <div class="card-header bg-gradient-to-r from-gray-50 to-white"><h3 class="text-lg font-bold text-gray-900">Add New Product</h3></div>
        <div class="card-body">
            <form action="/products/store" method="POST" class="space-y-5">
                <?= csrf_field() ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div><label class="form-label">Product Code *</label><input type="text" name="code" value="<?= old('code') ?>" class="form-input" required placeholder="e.g. OFF-003"></div>
                    <div><label class="form-label">Product Name *</label><input type="text" name="name" value="<?= old('name') ?>" class="form-input" required></div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div x-data="{ selectedCategory: '<?= old('category_id', '') ?>' }">
                        <label class="form-label">Category *</label>
                        <select name="category_id" x-model="selectedCategory" class="form-select" required>
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>"><?= esc($cat['name']) ?></option>
                            <?php endforeach; ?>
                            <option value="other">Other...</option>
                        </select>
                        <div x-show="selectedCategory === 'other'" x-cloak x-transition class="mt-2">
                            <input type="text" name="custom_category" class="form-input text-sm" placeholder="Enter new category name..." :required="selectedCategory === 'other'">
                        </div>
                    </div>
                    <div x-data="{ 
                        selectedUnit: '<?php 
                            $unitVal = old('unit_of_measurement', 'pieces');
                            echo in_array($unitVal, ['pieces','boxes','reams','rolls','kg','liters','meters','sets']) ? $unitVal : 'other';
                        ?>',
                        customUnitValue: '<?php
                            $unitVal = old('unit_of_measurement', '');
                            echo in_array($unitVal, ['pieces','boxes','reams','rolls','kg','liters','meters','sets']) ? '' : esc($unitVal);
                        ?>'
                    }">
                        <label class="form-label">Unit of Measurement *</label>
                        <select name="unit_of_measurement" x-model="selectedUnit" class="form-select" required>
                            <?php foreach (['pieces','boxes','reams','rolls','kg','liters','meters','sets'] as $u): ?>
                            <option value="<?= $u ?>"><?= ucfirst($u) ?></option>
                            <?php endforeach; ?>
                            <option value="other">Other...</option>
                        </select>
                        <div x-show="selectedUnit === 'other'" x-cloak x-transition class="mt-2">
                            <input type="text" name="custom_unit" x-model="customUnitValue" class="form-input text-sm" placeholder="Enter custom unit (e.g. packs, bottles)..." :required="selectedUnit === 'other'">
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    <div><label class="form-label">Initial Stock *</label><input type="number" name="quantity_in_stock" value="<?= old('quantity_in_stock', 0) ?>" class="form-input" min="0" required></div>
                    <div><label class="form-label">Reorder Level *</label><input type="number" name="reorder_level" value="<?= old('reorder_level', 10) ?>" class="form-input" min="0" required></div>
                    <div><label class="form-label">Expiration Date</label><input type="date" name="expiration_date" value="<?= old('expiration_date') ?>" class="form-input"></div>
                </div>
                <div>
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div><label class="form-label">Description</label><textarea name="description" class="form-input" rows="3"><?= old('description') ?></textarea></div>
                <div class="flex gap-3 pt-2"><button type="submit" class="btn-primary">Create Product</button><a href="/products" class="btn-secondary">Cancel</a></div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
