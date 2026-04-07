<template>
    <div class="card">
        <div class="card-header">
            <h5>Exhibitions</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive" id="table_container">
                <table class="table table-bordered" style="width: 100%" id="tableExhibitions" v-if="!loading">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>name</th>
                            <th>Exhibitions</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addExhibitionsToUser">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Assign Exhibitions To User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="input-1">Select Exhibitions</label>
                            <v-select class="form-control" placeholder="Select an Sub Exhibitions"
                                :options="list_exhibitions" label="label" :reduce="option => option.value"
                                v-model="selected_exhibitions" :clearable="false"></v-select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-inverse-primary" data-dismiss="modal"><i
                                class="fa fa-times"></i>
                            Close</button>
                        <button type="button" @click="assignExhibitionsToUser" class="btn btn-primary"><i :class="{
                            'fa fa-spinner fa-spin': disabled,
                            'fa fa-edit': !disabled,
                        }"></i>
                            Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import swalNotif from '../Utils/swalNotif.js';
import Swal from 'sweetalert2';

export default {
    data() {
        return {
            disabled: false,
            loading: true,
            location_id: this.locationId,
            note: "",
            tableExhibitions: null,
            list_exhibitions: [],
            selected_exhibitions: "",
            iduser: ""
        }
    },
    methods: {
        get_exhibitions() {
            const vm = this;
            this.tableExhibitions = $("#tableExhibitions").DataTable(
                {
                    processing: true,
                    serverSide: true,
                    ajax: {
                        type: "GET",
                        url: "/api/v1/web/exhibitions/get",
                        headers: {
                            token: localStorage.getItem('token')
                        }
                    },
                    pageLength: 25,
                    "columnDefs": [{
                        "width": "2%",
                        "targets": 0
                    }, {
                        "width": "2%",
                        "targets": 3
                    }],
                    columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    }, {
                        data: 'username',
                        name: 'username'
                    },
                    {
                        data: 'exhibitions',
                        name: 'exhibitions',
                        orderable: false,
                        searchable: false
                    }, {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                    ]
                }
            );
        },
        refresh_table() {
            const vm = this;
            vm.globalLoader.show = true;
            this.tableExhibitions.ajax.reload(() => {
                vm.globalLoader.show = false;
            });
        },
        change_show_status(idexhibitions, isShow) {
            const vm = this;
            vm.globalLoader.show = true;
            axios.post("/api/v1/web/exhibitions/show/change", {
                'idexhibitions': idexhibitions,
                'cmd': isShow
            }, {
                headers: {
                    token: localStorage.getItem('token'),
                }
            }).then(res => {
                if (res.data.status == 1) {
                    vm.refresh_table();
                    swalNotif.success(res.data.message);
                }
                else {
                    swalNotif.error(res.data.message);
                }
            }).catch(err => {
                swalNotif.error(err.response.data.message);
            }).finally(function () {
                vm.globalLoader.show = false;
            });
        },
        get_list_exhibitions() {
            const vm = this;
            vm.globalLoader.show = true;
            axios.post("/api/v1/web/exhibitions/list/get", {}, {
                headers: {
                    token: localStorage.getItem('token'),
                }
            }).then(res => {
                if (res.data.status == 1) {
                    vm.list_exhibitions = res.data.data.map(item => ({
                        label: item.name,
                        value: item.idexhibitions
                    }));
                    vm.selected_exhibitions = res.data.data[0].idexhibitions;
                }
                else {
                    swalNotif.error(res.data.message);
                }
            }).catch(err => {
                swalNotif.error("Error Getting Data!");
            }).finally(function () {
                vm.globalLoader.show = false;
            });
        },
        assignExhibitionsToUser() {
            const vm = this;
            vm.globalLoader.show = true;
            axios.post("/api/v1/web/exhibitions/assign/user/add", {
                'iduser': vm.iduser,
                'idexhibitions': vm.selected_exhibitions
            }, {
                headers: {
                    token: localStorage.getItem('token'),
                }
            }).then(res => {
                if (res.data.status == 1) {
                    vm.refresh_table();
                    $("#addExhibitionsToUser").modal('hide');
                    swalNotif.success(res.data.message);
                }
                else {
                    swalNotif.error(res.data.message);
                }
            }).catch(err => {
                swalNotif.error("Error Add Data!");
            }).finally(function () {
                vm.globalLoader.show = false;
            });
        },
        deleteAssignExhibitions(id) {
            const vm = this;
            vm.globalLoader.show = true;
            axios.post("/api/v1/web/exhibitions/assign/user/delete", {
                'id': id
            }, {
                headers: {
                    token: localStorage.getItem('token'),
                }
            }).then(res => {
                if (res.data.status == 1) {
                    vm.refresh_table();
                    swalNotif.success(res.data.message);
                }
                else {
                    swalNotif.error(res.data.message);
                }
            }).catch(err => {
                swalNotif.error("Error Deleting Data!");
            }).finally(function () {
                vm.globalLoader.show = false;
            });
        }
    },
    mounted() {
        const vm = this;
        this.loading = false;
        setTimeout(() => {
            vm.get_exhibitions();
            vm.get_list_exhibitions();

            $("#tableExhibitions").on('click', '.btnAdd', function () {
                const id = this.id;
                vm.iduser = id;
                $("#addExhibitionsToUser").modal({ backdrop: 'static', keyboard: false });
            });
            $("#tableExhibitions").on('click', '.btnExhibitions', function () {
                const id = this.id;
                Swal.fire({
                    icon: "warning",
                    title: "Warning",
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    text: "This Action Will Delete Assign Exhibitions!",
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Yes, delete it!",
                    cancelButtonText: "No, cancel!",
                    showCancelButton: true,
                    didOpen: () => {
                        Swal.showLoading();
                        setTimeout(() => { Swal.hideLoading() }, 500)
                    }
                }).then((result) => {
                    $(".confirm").attr('disabled', 'disabled');
                    if (result.isConfirmed) {
                        vm.deleteAssignExhibitions(id);
                    }
                });
            });

        }, 1);
    },
}
</script>
