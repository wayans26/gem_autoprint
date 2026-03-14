<template>
    <div class="card">
        <div class="card-header">
            <h5>Visitor Print</h5>
        </div>
        <div class="card-body">
            <form @submit.prevent="print_barcode">
                <input ref="barcode_field" name="barcode" type="text" class="form-control" placeholder="Barcode *"
                    v-model="barcode" autofocus :disabled="disabled" @blur="$refs.barcode_field.focus()">
                </input>
                <p class="text-mute">{{ status }} | {{ printer_name }}</p>
                <button type="button" class="btn btn-primary ml-1" @click="launchQzTray" v-show="!connected">Launch
                    Printer</button>
                <button type="button" class="btn btn-primary ml-1" @click="connectQzTray" v-show="!connected">Connect
                    Printer</button>
                <button type="submit" class="btn btn-primary ml-1" v-show="connected" :disabled="disabled">Test
                    Print</button>
            </form>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import swalNotif from '../Utils/swalNotif.js';
import qz from "qz-tray";
import notification from '../Utils/notification.js';

export default {
    data() {
        return {
            disabled: false,
            loading: true,
            barcode: "",

            // Printer
            status: "Printer Not Connected",
            printer_name: "",
            connected: false,
            connecting: false,
            showLaunchHint: false,
            cfg: null,
            data_print: "",
            data_config: {
                colorType: "color",
                copies: 1,
                density: 0,
                duplex: false,
                fallbackDensity: null,
                interpolation: "bicubic",
                jobName: null,
                margins: 0,
                orientation: null,
                paperThickness: null,
                printerTray: null,
                rasterize: true,
                rotation: 0,
                scaleContent: true,
                size: null,
                units: "in",
                altPrinting: false,
                encoding: null,
                endOfDoc: null,
                perSpool: 1
            }
        }
    },
    methods: {
        esc(text = "") {
            return String(text).replace(/"/g, "'");
        },
        async print_barcode() {
            if (this.barcode.length < 1) {
                notification.notif_info("Please Input Barcode");
                return;
            }
            if (!this.connected) {
                await this.connectQzTray();
            }
            const vm = this;
            vm.globalLoader.show = true;
            vm.disabled = true;
            axios.post("/api/v1/web/visitor/print", {
                'barcode': vm.barcode,
            }, {
                headers: {
                    token: localStorage.getItem('token'),
                }
            }).then(async res => {
                if (res.data.status == 1) {
                    vm.data_print = res.data.data;
                    await vm.print();
                }
                else {
                    notification.notif_error(res.data.message);
                }
            }).catch(err => {
                notification.notif_error("Error Internal Server");
            }).finally(function () {
            });
        },
        updatedConfigure() {
            this.cfg.reconfigure({
                colorType: this.data_config.colorType,
                copies: this.data_config.copies,
                density: this.data_config.density,
                duplex: this.data_config.duplex,
                fallbackDensity: this.data_config.fallbackDensity,
                interpolation: this.data_config.interpolation,
                jobName: this.data_config.jobName,
                margins: this.data_config.margins,
                orientation: this.data_config.orientation,
                paperThickness: this.data_config.paperThickness,
                printerTray: this.data_config.printerTray,
                rasterize: this.data_config.rasterize,
                rotation: this.data_config.rotation,
                scaleContent: this.data_config.scaleContent,
                size: this.data_config.size,
                units: this.data_config.units,
                altPrinting: this.data_config.altPrinting,
                encoding: this.data_config.encoding,
                endOfDoc: this.data_config.endOfDoc,
                perSpool: this.data_config.perSpool
            });
        },
        getUpdateConfigure() {
            if (this.cfg == null) {
                cfg = qz.configs.create(null);
            }
            this.updatedConfigure();
        },
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
        launchQzTray() {
            if (this.connected) {
                swalNotif.info("Printer Already Connected");
                return;
            }
            if (!this.connecting) {
                window.location.href = "qz:launch";
                setTimeout(async () => {
                    await this.connectQzTray();
                }, 5000);
            }
            else {
                swalNotif.info("Connecting In Progress");
                return;
            }
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
                    this.printer_name = await qz.printers.getDefault();
                    // this.printer_name = "Argox CP-2140 PPLB"
                    this.cfg = qz.configs.create(this.printer_name);
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
        async print() {
            if (!this.connected) {
                notification.notif_info("Please Launch Printer First");
                return;
            }
            this.status = `Printing on ${this.printer_name}`;
            if (!this.data_print) {
                notification.notif_info("Data Not Found");
                return;
            }
            if (this.cfg == null) {
                notification.notif_info("Please Install Your Printer Driver");
                return;
            }
            await qz.print(this.cfg, [{ type: "raw", format: "plain", data: this.data_print }]);
            this.status = `Printed Successfully`;
            this.globalLoader.show = false;
            this.barcode = "";
            this.disabled = false;
        },

    },
    mounted() {
        this.setupQzSecureOnce();
        if (qz.websocket.isActive()) {
            this.connected = true;
            this.connecting = false;
            this.status = "Printer Connected";
        }
        const vm = this;
        this.loading = false;
    },
    beforeUnmount() {
        this.safeDiconnect();
    }
}
</script>
