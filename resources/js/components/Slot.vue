<template>
    <div class="flex-center position-ref full-height">
        <div class="content">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-content">
                                <div class="content">
                                    <h2>Parking Lot</h2>
                                    <p>ระบบแสดงสถานะที่จอดรถ</p>
                                    <h1 v-if="empty > 0" class="has-text-success">ว่าง {{ empty }} ช่อง</h1>
                                    <h1 v-else-if="empty === 0" class="has-text-danger">ที่จอดเต็ม</h1>
                                    <h1 v-else><div class="button is-loading" style="height: 1rem; border: 0px;"></div></h1>
                                </div>
                                <div class="content">
                                    <div class="content">
                                        <div style="overflow-x: auto;">
                                            <table style="display: block !important;max-width: 100vw;" class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
                                                <thead>
                                                    <tr>
                                                        <th>ช่องจอด</th>
                                                        <th class="has-text-centered">สถานะ</th>
                                                        <th>เวลาที่จอด(ชั่วโมง)</th>
                                                        <th>เริ่มจอดเมื่อ</th>
                                                        <th>นับครั้ง(เดือนนี้)</th>
                                                        <th>นับครั้ง(ทั้งหมด)</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="slot in slots" v-bind:key="slot.id">
                                                        <td>{{ slot.name }}</td>
                                                        <td v-if="slot.status == 'busy'" class="has-text-centered"><span class="has-text-danger"><i class="fas fa-times-circle"></i> <small>ไม่ว่าง</small></span></td>
                                                        <td v-else-if="slot.status == 'ready'" class="has-text-centered"><span class="has-text-success"><i class="fas fa-check-circle"></i> <small>ว่าง</small></span></td>
                                                        <td v-else class="has-text-centered"><span class="has-text-warning"><i class="fas fa-exclamation-circle"></i> <small>ผิดพลาด</small></span></td>
                                                        <td>{{ slot.status == 'busy' ? slot.present_time : '-' }}</td>
                                                        <td>{{ slot.status == 'busy' ? slot.last_in.created_at : '-' }}</td>
                                                        <td>{{ slot.count_in_month }}</td>
                                                        <td>{{ slot.count_in }}</td>
                                                    </tr>
                                                    <tr id="data-loader">
                                                        <td colspan="6" class="has-text-centered" style="line-height: 1rem;"><div class="button is-loading" style="height: 1rem; border: 0px;"></div></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="content" style="margin-top:2rem;">
                                        <p class="has-text-centered">
                                            <small>อัพเดทล่าสุดเมื่อ<br><span>{{ dateTime }}</span></small>
                                        </p>
                                        <router-link tag="a" to="/">
                                            <button class="button is-primary">
                                                <i class="fas fa-arrow-circle-right" style="margin-right:0.5rem;"></i>
                                                กลับหน้าหลัก
                                            </button>
                                        </router-link>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        const appUrl = window.location.href.split('/')[2];
        const slots = [];
        const empty = null;
        const dateTime = "";

        return {
            componentName: 'Slot',
            appUrl,
            slots,
            empty,
            dateTime
        }
    },
    created() {
        this.setup();
    },
    mounted() {
        console.log(`Component ${this.componentName} mounted.`);

        setInterval(() => {
            this.setupSlots();
        }, 5000);
    },
    methods: {
        async setup() {
            await this.setupSlots();
        },
        async setupSlots() {
            const slots = await this.fetchSlots();

            this.slots = slots.data.data;
            this.empty = slots.empty;
            this.getDatetimeNow();

            // Data Loader Animation
            if (this.slots.length > 0) {
                document.getElementById('data-loader').style.display = 'none';
            } else {
                document.getElementById('data-loader').style.display = 'block';
            }
        },
        async fetchSlots() {
            let result = await axios.get(`http://${this.appUrl}/slots`)
                .catch((error) => {
                    // handle error
                    console.log(error);
                })
            return result.data;
        },
        getDatetimeNow() {
            var today = new Date();
            var dd = today.getDate();
            var mm = today.getMonth() + 1; //January is 0!
            var yyyy = today.getFullYear();
            var hour = today.getHours();
            var minute = today.getMinutes();
            var second = today.getSeconds();
            if (dd < 10) {
                dd = '0' + dd;
            }
            if (mm < 10) {
                mm = '0' + mm;
            }

            this.dateTime = `${dd}/${mm}/${yyyy} ${hour}:${minute}:${second}`;
        }
    }
}
</script>

<style>
    html, body {
        color: #636b6f;
        font-family: 'Nunito', sans-serif;
        font-weight: 200;
        height: 100vh;
        margin: 0;
    }

    .full-height {
        height: 100vh;
    }

    .flex-center {
        align-items: center;
        display: flex;
        justify-content: center;
    }

    .position-ref {
        position: relative;
    }

    .top-right {
        position: absolute;
        right: 10px;
        top: 18px;
    }

    .content {
        text-align: center;
    }

    .title {
        font-size: 84px;
    }

    .links > a {
        color: #636b6f;
        padding: 0 25px;
        font-size: 13px;
        font-weight: 600;
        letter-spacing: .1rem;
        text-decoration: none;
        text-transform: uppercase;
    }

    .m-b-md {
        margin-bottom: 30px;
    }
</style>
