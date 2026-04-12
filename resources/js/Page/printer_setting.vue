<template>
    <div class="card">
        <div class="card-header">
            <h5>Printer Setting
                <button class="btn btn-primary m-1" @click="refreshPrinter">Refresh Printer</button>
            </h5>
        </div>
        <div class="card-body">
            <p>Status : {{ status }}</p>
            <p>Printer Name : {{ saved_printed }}</p>
            <div class="form-group">
                <label for="input-1">Printers</label>
                <v-select class="form-control" placeholder="Select an Printer Name" :options="list_printer"
                    label="label" :reduce="option => option.value" v-model="printer_name" :clearable="false"></v-select>
            </div>
        </div>
        <div class="card-footer">
            <button class="btn btn-success m-1" @click="setPrinter">Update Setting</button>
            <button class="btn btn-success m-1" v-show="!connected" @click="connectQzTray">Connect</button>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import swalNotif from '../Utils/swalNotif.js';
import qz from "qz-tray";

export default {
    data() {
        return {
            // Printer
            status: "Printer Not Connected",
            printer_name: "",
            saved_printed: localStorage.getItem("printer_name"),
            list_printer: [],
            connected: false,
            connecting: false,
            cfg: null,
        }
    },
    methods: {
        setupQzSecureOnce() {
            qz.security.setCertificatePromise(function (resolve, reject) {
                axios.get("/api/v1/web/qz/cert", {
                    headers: { token: localStorage.getItem("token") },
                })
                    .then((res) => {
                        const certPem = res.data.data;
                        if (typeof certPem !== "string" || !certPem.includes("BEGIN CERTIFICATE")) {
                            throw new Error("CERT is not valid PEM string");
                        }
                        resolve(certPem);
                    });
            });
            qz.security.setSignatureAlgorithm("SHA256");
            qz.security.setSignaturePromise(function (toSign) {
                return function (resolve, reject) {
                    // resolve();
                    axios.post("/api/v1/web/qz/sign", {
                        toSign: toSign
                    }, {
                        headers: { token: localStorage.getItem("token") },
                    })
                        .then((res) => {
                            const certPem = res.data.data;
                            resolve(certPem);
                        });
                };
            });
        },
        async connectQzTray() {
            if (this.connecting) {
                swalNotif.info("Connecting In Progress");
                return;
            }
            this.connecting = true;
            if (qz.websocket.isActive()) {
                this.connected = true;
                this.connecting = false;
                this.status = "Printer Already Connected";
            }
            else {
                this.status = "Connectiong...";
                await qz.websocket.connect({ retries: 5, delay: 1 }).then(async () => {
                    this.connected = true;
                    this.status = "Printer Connected";

                    const result = await qz.printers.find();
                    this.list_printer = Array.isArray(result) ? result.map(item => (
                        {
                            label: item,
                            value: item
                        }
                    )) : [result];
                    // if (this.list_printer.length > 0 || !this.printer_name) {
                    //     this.printer_name = this.list_printer[0];
                    // }

                    // this.cfg = qz.configs.create(this.printer_name);
                    this.connecting = false;
                }).catch((err) => {
                    swalNotif.error("Please Launch Printer First");
                    this.status = "Printer Not Connected";
                    this.connecting = false;
                });
            }
        },
        async safeDiconnect() {
            try {
                if (qz.websocket.isActive()) {
                    this.status = "Disconnecting...";

                    await qz.websocket.disconnect();
                    this.status = "Printer Disconnected";
                    this.connected = false;
                    this.connecting = false;
                    this.printer_name = "";
                } else {
                    this.status = "Printer Not Connected";
                }
            } catch (err) {
                console.error("Failed to disconnect:", err);
            }
        },
        async refreshPrinter() {
            if (!qz.websocket.isActive()) {
                swalNotif.error("Printer Not Connected");
                return;
            }
            this.status = "Refreshing...";
            const result = await qz.printers.find();
            this.list_printer = Array.isArray(result) ? result.map(item => (
                {
                    label: item,
                    value: item
                }
            )) : [result];
            if (this.list_printer.length > 0 || !this.printer_name) {
                this.printer_name = this.list_printer[0];
            }
            this.status = "Printer Refreshed";
        },
        setPrinter() {
            if (!this.printer_name) {
                swalNotif.error("Please Select Printer");
                return;
            }
            localStorage.setItem("printer_name", this.printer_name);
            this.saved_printed = localStorage.getItem("printer_name");
            swalNotif.success("Printer Setting Updated");
        }

    },
    mounted() {
        this.setupQzSecureOnce();
        setTimeout(() => {
            this.connectQzTray();
        }, 1000);
    },
    beforeUnmount() {
        this.safeDiconnect();

    }
}
</script>
