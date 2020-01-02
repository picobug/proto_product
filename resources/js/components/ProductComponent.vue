<template>
    <div class="products">
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
                    <td>{{ key + 1 }}</td>
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
            roles: [],
            error: ''
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
        async getRoles() {
            try {
                let r = await axios.get("/api/user");
                this.roles = r.data.roles;
            } catch (err) {}
        },
        async getProducts() {
            try {
                let r = await axios.get("/api/products");
                this.lists = r.data.data;
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
                } else {
                    let r = await axios.post("/api/products", form);
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
                    this.getProducts();
                } catch (err) {}
            }
        }
    }
};
</script>
