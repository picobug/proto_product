<template>
    <div class="app_modal">
        <div class="modal-backdrop fade show"></div>
        <div class="modal fade show d-block" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            {{ edit ? "Ubah Produk" : "Tambah Produk" }}
                        </h5>
                        <button
                            type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-label="Close"
                            @click="close"
                        >
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="kirim">
                            <div class="form-group">
                                <label for="productCode">Kode Barang</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    required="required"
                                    id="productCode"
                                    v-model="form.code"
                                    placeholder="Isi Kode Barang"
                                />
                            </div>
                            <div class="form-group">
                                <label for="productName">Nama Barang</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    required="required"
                                    id="productName"
                                    v-model="form.name"
                                    placeholder="Nama Barang"
                                />
                            </div>
                            <div class="form-group">
                                <label for="productStock">Stok</label>
                                <input
                                    type="number"
                                    class="form-control"
                                    required="required"
                                    id="productStock"
                                    v-model.number="form.stock"
                                    placeholder="Stok Barang"
                                />
                            </div>
                            <div class="form-group">
                                <label for="productSell">Harga Jual</label>
                                <input
                                    type="number"
                                    class="form-control"
                                    required="required"
                                    id="productSell"
                                    v-model.number="form.sell_price"
                                    placeholder="Harga Jual"
                                />
                            </div>
                            <div class="form-group">
                                <label for="productBuy">Harga Beli</label>
                                <input
                                    type="number"
                                    class="form-control"
                                    required="required"
                                    id="productBuy"
                                    v-model.number="form.buy_price"
                                    placeholder="Harga Beli"
                                />
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <p v-if="error" class="text-danger">{{ error }}</p>
                        <p v-if="fail" class="text-danger">{{ fail }}</p>
                        <button
                            type="button"
                            class="btn btn-primary"
                            @click="kirim"
                        >
                            Save changes
                        </button>
                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-dismiss="modal"
                            @click="close"
                        >
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { required } from "vuelidate/lib/validators";

export default {
    name: "FormProduct",
    props: {
        form: {
            type: Object,
            default: () => ({
                code: "",
                name: "",
                stock: "",
                sell_price: "",
                buy_price: ""
            })
        },
        edit: {
            type: Boolean,
            default: false
        },
        fail: {
            type: String,
            default: ''
        }
    },
    data() {
        return {
            error: ""
        };
    },
    validations: {
        form: {
            code: {
                required
            },
            name: {
                required
            },
            stock: {
                required
            },
            sell_price: {
                required
            },
            buy_price: {
                required
            }
        }
    },
    methods: {
        close() {
            this.$emit("close");
        },
        kirim() {
            this.error = ''
            this.$v.$touch();
            if (this.$v.$invalid) {
                this.error = "ERROR";
            } else {
                this.$emit("kirim", this.form);
            }
        }
    }
};
</script>
