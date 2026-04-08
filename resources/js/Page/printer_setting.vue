<template>
    <div class="card">
        <div class="card-header">
            <h5>Printer Setting
                <!-- <button class="btn btn-primary m-1" @click="refreshPrinter">Refresh Printer</button> -->
            </h5>
        </div>
        <div class="card-body">
            <p>Status : {{ status }}</p>
            <div class="form-group">
                <label for="input-1">Printers</label>
                <v-select class="form-control" placeholder="Select an Printer Name" :options="list_printer"
                    label="label" :reduce="option => option.value" v-model="printer_name" :clearable="false"></v-select>
            </div>
        </div>
        <div class="card-footer">
            <button class="btn btn-success m-1" @click="setPrinter">Update Setting</button>
        </div>
    </div>
</template>

<script>
import * as yup from 'yup';
import { Form, Field, ErrorMessage } from 'vee-validate';
import axios from 'axios';
import swalNotif from '../Utils/swalNotif.js';
import qz from "qz-tray";
import notification from '../Utils/notification.js';

export default {
    components: {
        Form,
        Field,
        ErrorMessage
    },
    data() {
        return {
            disabled: false,
            hasExhibitions: true,
            loading: true,
            barcode: "",
            name: "",
            title: "",
            company: "",
            email: "",
            phone: "",
            country: "ID",
            exhibitions: "",
            sub_exhibitions: "",
            list_exhibitions: [],
            list_sub_exhibitions: [],
            list_country: [],

            // Printer
            status: "Printer Not Connected",
            printer_name: localStorage.getItem("printer_name"),
            list_printer: [],
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
                perSpool: 1,
            },
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
                    // this.printer_name = await qz.printers.getDefault();
                    // this.printer_name = "Argox CP-2140 PPLB"
                    // this.list_print = await qz.printers.find();

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
            this.initValue();
            notification.notif_success("Printed Successfully");
            this.$nextTick(() => {
                this.$refs.name_visitor.focus();
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
            this.globalLoader.show = false;
            this.barcode = "";
            this.disabled = false;
        },
        setPrinter() {
            if (!this.printer_name) {
                swalNotif.error("Please Select Printer");
                return;
            }
            localStorage.setItem("printer_name", this.printer_name);
            swalNotif.success("Printer Setting Updated");
        }

    },
    mounted() {
        this.setupQzSecureOnce();
        if (qz.websocket.isActive()) {
            this.connected = true;
            this.connecting = false;
            this.status = "Printer Connected";
        }
        const vm = this;
        if (this.printer_name) {
            this.connectQzTray();
        }
        this.loading = false;
        this.getRegisterData();
    },
    beforeUnmount() {
        this.safeDiconnect();

    }
}
</script>
