<template>
    <div class="products">
        <div v-if="message" class="alert alert-info alert-dismissible fade show" role="alert">
            {{ message }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close" @click="message = ''">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <button class="btn btn-primary" @click="modal = true">
            Tambah Barang
        </button>
        <table class="mt-4 table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Stok</th>
                    <th v-if="!staff">Harga Jual</th>
                    <th v-if="!staff && !admin">Harga Beli</th>
                    <th v-if="owner">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(list, key) in lists">
                    <td>{{ key + paginate.from }}</td>
                    <td>{{ list.code }}</td>
                    <td>{{ list.name }}</td>
                    <td>{{ list.stock }}</td>
                    <td v-if="!staff">{{ list.sell_price }}</td>
                    <td v-if="!staff && !admin">{{ list.buy_price }}</td>
                    <td v-if="owner">
                        <button class="btn btn-primary" @click="setEdit(list)">
                            Edit
                        </button>
                        <button class="btn btn-danger" @click="hapus(list)">
                            Delete
                        </button>
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="7">
                        <button
                            class="btn btn-primary"
                            :disabled="paginate.current_page === 1"
                            @click="callPrev"
                        >
                            Prev
                        </button>
                        <button
                            class="btn btn-primary float-right"
                            :disabled="
                                paginate.current_page === paginate.last_page
                            "
                            @click="callNext"
                        >
                            Next
                        </button>
                    </td>
                </tr>
            </tfoot>
        </table>
        <app-form
            :form="form"
            v-if="modal"
            :edit="edit"
            :fail="error"
            @close="modal = false"
            @kirim="kirim"
        />
    </div>
</template>

<script>
import FormProduct from "./FormProduct.vue";
export default {
    components: {
        appForm: FormProduct
    },
    data() {
        return {
            form: {},
            edit: false,
            modal: false,
            lists: [],
            paginate: {
                current_page: 1,
                from: 1,
                last_page: 1,
                per_page: 1
            },
            message: '',
            roles: [],
            error: ""
        };
    },
    computed: {
        owner() {
            let exist = false;
            this.roles.map(role => {
                if (role === "owner") exist = true;
            });
            return exist;
        },
        staff() {
            let exist = false;
            this.roles.map(role => {
                if (role === "staff") exist = true;
            });
            return exist;
        },
        admin() {
            let exist = false;
            this.roles.map(role => {
                if (role === "admin") exist = true;
            });
            return exist;
        }
    },
    mounted() {
        this.getProducts();
        this.getRoles();
    },
    methods: {
        callPrev() {
            this.$set(
                this.paginate,
                "current_page",
                this.paginate.current_page - 1
            );
            this.getProducts();
        },
        callNext() {
            this.$set(
                this.paginate,
                "current_page",
                this.paginate.current_page + 1
            );
            this.getProducts();
        },
        async getRoles() {
            try {
                let r = await axios.get("/api/user");
                this.roles = r.data.roles;
            } catch (err) {}
        },
        async getProducts() {
            try {
                let r = await axios.get("/api/products", {
                    params: {
                        page: this.paginate.current_page
                    }
                });
                let data = r.data;
                this.lists = data.data;
                this.$set(this.paginate, "current_page", data.current_page);
                this.$set(this.paginate, "from", data.from);
                this.$set(this.paginate, "last_page", data.last_page);
                this.$set(this.paginate, "per_page", data.per_page);
            } catch (err) {}
            this.form = {};
            this.modal = false;
            this.edit = false;
            this.error = "";
        },
        async kirim(form) {
            try {
                if (form.id) {
                    let r = await axios.post(`/api/products/${form.id}`, form);
                    this.message = `Produk ${form.name} berhasil di perbaharui`
                } else {
                    let r = await axios.post("/api/products", form);
                    this.message = `Produk ${form.name} berhasil di buat`
                    this.paginate = {
                        current_page: 1,
                        from: 1,
                        last_page: 1
                    };
                }
                this.getProducts();
            } catch (err) {
                this.error = err.response.data.message;
            }
        },
        setEdit(list) {
            this.edit = true;
            this.modal = true;
            this.form = JSON.parse(JSON.stringify(list));
        },
        async hapus(list) {
            let status = confirm(`Yakin akan hapus barang : ${list.name}?`);
            if (status) {
                try {
                    let r = await axios.delete(`/api/products/${list.id}`);
                    this.paginate = {
                        current_page: 1,
                        from: 1,
                        last_page: 1,
                        per_page: 1
                    };
                    this.message = `Produk ${list.name} berhasil di hapus`
                    this.getProducts();
                } catch (err) {
                    this.message = `Produk ${list.name} gagal di hapus`
                }
            }
        }
    }
};
</script>
