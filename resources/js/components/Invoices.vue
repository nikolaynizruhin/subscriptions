<template>
    <div>
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl">Invoices</h3>
        </div>
        <div class="w-full card">
            <div v-if="loading" class="flex items-center justify-center p-6">
                <span class="spinner text-gray-500 w-6 h-6"/>
            </div>
            <div v-else-if="hasNoInvoices" class="p-6 flex flex-col items-center">
                <icon name="file-text" class="text-gray-500 mb-2" stroke-width="1" width="56" height="56"/>
                <p class="text-gray-500">No invoices.</p>
            </div>
            <table v-else class="table-auto w-full">
                <thead>
                    <tr>
                        <th class="py-4 font-medium px-6 bg-gray-100">Number</th>
                        <th class="py-4 font-medium px-6 bg-gray-100">Date</th>
                        <th class="py-4 font-medium px-6 bg-gray-100">Amount</th>
                        <th class="py-4 font-medium px-6 bg-gray-100">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    <tr
                        v-for="(invoice, index) in invoices"
                        :key="index"
                        :class="{ 'border-b': isNotLastInvoice(index) }"
                    >
                        <td class="py-4 px-6 border-t border-gray-300">
                            {{ invoice.number }}
                        </td>
                        <td class="py-4 px-6 border-t border-gray-300">
                            {{ invoice.created | date }}
                        </td>
                        <td class="py-4 px-6 border-t border-gray-300">
                            {{ invoice.total | money }}
                        </td>
                        <td class="py-4 px-6 border-t border-gray-300">
                            <a :href="invoice.invoice_pdf" class="flex justify-center">
                                <icon name="download" class="text-gray-500 hover:text-blue-500" width="20" height="20"/>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
export default {
    name: "Invoices",
    data () {
        return {
            invoices: [],
            loading: true,
        }
    },
    computed: {
        hasNoInvoices () {
            return !this.invoices.length
        }
    },
    methods: {
        async getInvoices () {
            const { data } = await axios.get('/api/invoices')

            this.invoices = data
        },
        isNotLastInvoice (index) {
            return index !== this.invoices.length - 1
        },
    },
    async mounted () {
        await this.getInvoices()

        this.loading = false
    }
}
</script>

<style scoped>

</style>
