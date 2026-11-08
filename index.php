<!DOCTYPE html>
<html lang="en" class="min-h-full bg-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dettes clients — Snack & Cave | Complexe Saint Joseph</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- FontAwesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- KaTeX CDN for Math rendering -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.8/dist/katex.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.8/dist/katex.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.8/dist/contrib/auto-render.min.js" onload="renderMathInElement(document.body);"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            500: '#10b981',
                            600: '#047857',
                            700: '#065f46',
                            900: '#064e3b',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        nav > button { flex: 0 0 auto; }
        .saas-shell { background: #f5f7f6; }
        .saas-shell .rounded-xl { border-radius: 8px !important; }
        .saas-shell .saas-sidebar nav > button { justify-content: flex-start; min-height: 42px; border-radius: 8px; }
        .saas-shell .saas-sidebar nav > button:not(.bg-brand-600) { color: #64748b; }
        .saas-shell .saas-sidebar nav > button:hover:not(.bg-brand-600) { background: #f1f5f9; color: #0f172a; }
        .saas-shell .saas-sidebar .text-slate-400 { color: #64748b; }
        @media (min-width: 1024px) {
            .saas-shell > main { max-width: none; margin: 0; min-width: 0; }
            .saas-shell .saas-sidebar > div { max-width: none; height: 100%; margin: 0; padding: 28px 16px; }
            .saas-shell .saas-sidebar > div > div { height: 100%; align-items: stretch; justify-content: flex-start; gap: 28px; }
            .saas-shell .saas-sidebar nav { width: 100%; max-width: none; flex-direction: column; align-items: stretch; overflow: visible; white-space: normal; }
        }
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        @media print {
            body * { visibility: hidden !important; }
            #print-report, #print-report * { visibility: visible !important; }
            #print-report { display: block !important; position: absolute; inset: 0; padding: 18mm; color: #111827; font: 10pt Arial, sans-serif; }
            #print-report h1, #print-report h2 { margin: 0 0 8px; text-align: center; }
            #print-report table { width: 100%; margin: 18px 0; border-collapse: collapse; }
            #print-report th, #print-report td { padding: 7px; border: 1px solid #9ca3af; text-align: left; }
            #print-report th { background: #e5e7eb !important; }
        }
    </style>
</head>
<body class="saas-shell min-h-screen text-slate-800 flex flex-col lg:flex-row font-sans" x-data="debtApp()" x-init="initData()">

    <!-- Header / Navbar -->
    <header class="saas-sidebar bg-white text-slate-900 border-b border-slate-200 sticky top-0 z-30 shadow-sm lg:h-screen lg:w-64 lg:shrink-0 lg:border-b-0 lg:border-r">
        <div class="max-w-none w-full h-full mx-auto px-4 sm:px-6 lg:px-4">
            <div class="flex flex-col items-stretch gap-3 py-3 sm:flex-row sm:items-center sm:justify-between sm:min-h-16 lg:h-full lg:flex-col lg:items-stretch lg:justify-start lg:gap-7 lg:py-7">
                <div class="flex items-center space-x-3">
                    <div class="p-2 bg-brand-600 rounded-lg text-white">
                        <i class="fa-solid fa-wallet text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold tracking-tight">Complexe Saint Joseph</h1>
                        <p class="text-xs text-slate-500">Gestion commerciale · Snack & Cave</p>
                    </div>
                </div>

                <!-- Navigation Tabs -->
                <nav class="flex w-full flex-nowrap gap-1 overflow-x-auto whitespace-nowrap sm:w-auto sm:max-w-[75%] lg:w-full lg:max-w-none lg:flex-col lg:items-stretch lg:overflow-visible lg:whitespace-normal">
                    <button @click="currentTab = 'dashboard'" :class="currentTab === 'dashboard' ? 'bg-brand-600 text-white' : ''" class="px-3 py-2 rounded-md text-sm font-medium transition-colors flex items-center space-x-2">
                        <i class="fa-solid fa-chart-pie"></i>
                        <span class="hidden sm:inline">Tableau de Bord</span>
                    </button>
                    <button @click="currentTab = 'debts'" :class="currentTab === 'debts' ? 'bg-brand-600 text-white' : ''" class="px-3 py-2 rounded-md text-sm font-medium transition-colors flex items-center space-x-2">
                        <i class="fa-solid fa-receipt"></i>
                        <span class="hidden sm:inline">Dettes & Paiements</span>
                    </button>
                    <button @click="currentTab = 'agent'" :class="currentTab === 'agent' ? 'bg-brand-600 text-white' : ''" class="px-3 py-2 rounded-md text-sm font-medium"><i class="fa-solid fa-wand-magic-sparkles mr-1"></i>Agent IA</button>
                    <button @click="selectSector('Snack')" class="px-3 py-2 rounded-md text-sm font-medium">Snack</button>
                    <button @click="selectSector('Cave')" class="px-3 py-2 rounded-md text-sm font-medium">Cave</button>
                    <button @click="currentTab = 'payments'" :class="currentTab === 'payments' ? 'bg-brand-600 text-white' : ''" class="px-3 py-2 rounded-md text-sm font-medium">Paiements</button>
                    <button @click="currentTab = 'clients'" :class="currentTab === 'clients' ? 'bg-brand-600 text-white' : ''" class="px-3 py-2 rounded-md text-sm font-medium transition-colors flex items-center space-x-2">
                        <i class="fa-solid fa-users"></i>
                        <span class="hidden sm:inline">Clients</span>
                    </button>
                    <button @click="currentTab = 'history'" :class="currentTab === 'history' ? 'bg-brand-600 text-white' : ''" class="px-3 py-2 rounded-md text-sm font-medium transition-colors flex items-center space-x-2">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                        <span class="hidden sm:inline">Historique des dettes</span>
                    </button>
                    <button @click="currentTab = 'history'" class="px-3 py-2 rounded-md text-sm font-medium">Rapports</button>
                    <button @click="currentTab = 'admin'" :class="currentTab === 'admin' ? 'bg-brand-600 text-white' : ''" class="px-3 py-2 rounded-md text-sm font-medium transition-colors flex items-center space-x-2">
                        <i class="fa-solid fa-gear"></i>
                        <span class="hidden sm:inline">Administration</span>
                    </button>
                    <button @click="currentTab = 'architecture'" :class="currentTab === 'architecture' ? 'bg-brand-600 text-white' : ''" class="px-3 py-2 rounded-md text-sm font-medium transition-colors flex items-center space-x-2">
                        <i class="fa-solid fa-code"></i>
                        <span class="hidden sm:inline">Architecture & Spécifications</span>
                    </button>
                </nav>
            </div>
        </div>
    </header>

    <!-- Main Content Container -->
    <main class="flex-1 bg-slate-50 py-6 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto w-full lg:max-w-none lg:mx-0 lg:min-w-0">
        <div x-show="loadError" x-cloak class="mb-5 rounded-lg border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800" role="alert">
            <strong>Connexion indisponible.</strong> <span x-text="loadError"></span>
            <p class="mt-1">Vérifiez qu’Apache et MySQL sont démarrés. Si l’erreur persiste, consultez le journal PHP.</p>
            <button @click="retryData()" class="mt-3 rounded-md border border-rose-300 bg-white px-3 py-1.5 text-xs font-semibold text-rose-800 hover:bg-rose-100">Réessayer</button>
        </div>

        <!-- ==================================================================== -->
        <!-- TAB 1: DASHBOARD -->
        <!-- ==================================================================== -->
        <div x-show="currentTab === 'dashboard'" x-cloak space-y-6>
            <!-- Welcome Header & Quick Actions -->
            <div class="md:flex md:items-center md:justify-between border-b border-slate-200 pb-4">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900">Aperçu Financier</h2>
                    <p class="text-sm text-slate-500">Suivi des ventes à crédit et des règlements du Snack et de la Cave.</p>
                </div>
                <div class="mt-4 md:mt-0 flex space-x-3">
                    <button @click="openModal('addClient')" class="inline-flex items-center px-4 py-2 border border-slate-300 rounded-lg shadow-sm text-sm font-medium text-slate-700 bg-white hover:bg-slate-50 focus:outline-none">
                        <i class="fa-solid fa-user-plus mr-2 text-slate-500"></i> Nouveau Client
                    </button>
                    <button @click="openNewDebtModal()" class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-brand-600 hover:bg-brand-700 focus:outline-none">
                        <i class="fa-solid fa-file-circle-plus mr-2"></i> Nouvelle Facture / Dette
                    </button>
                </div>
            </div>

            <!-- Global Key Performance Indicators (KPIs) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Total Debt -->
                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total Dettes Accordées</p>
                        <p class="text-2xl font-extrabold text-slate-900 mt-1" x-text="formatMoney(stats.totalAmount)"></p>
                        <span class="text-xs text-slate-500 font-medium"><span x-text="stats.debtCount"></span> créance(s) active(s)</span>
                    </div>
                    <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                        <i class="fa-solid fa-file-invoice-dollar text-2xl"></i>
                    </div>
                </div>

                <!-- Total Recovered -->
                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-emerald-600">Total Récupéré</p>
                        <p class="text-2xl font-extrabold text-emerald-700 mt-1" x-text="formatMoney(stats.totalPaid)"></p>
                        <span class="text-xs text-emerald-600 font-medium" x-text="stats.totalAmount > 0 ? Math.round((stats.totalPaid / stats.totalAmount) * 100) + '% d\'encaissement' : '0%'"></span>
                    </div>
                    <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl">
                        <i class="fa-solid fa-circle-check text-2xl"></i>
                    </div>
                </div>

                <!-- Total Remaining -->
                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-rose-600">Total Restant à Recouvrer</p>
                        <p class="text-2xl font-extrabold text-rose-700 mt-1" x-text="formatMoney(stats.totalRemaining)"></p>
                        <span class="text-xs text-rose-500 font-medium" x-text="stats.totalAmount > 0 ? Math.round((stats.totalRemaining / stats.totalAmount) * 100) + '% non soldé' : '0%'"></span>
                    </div>
                    <div class="p-3 bg-rose-50 text-rose-600 rounded-xl">
                        <i class="fa-solid fa-hand-holding-dollar text-2xl"></i>
                    </div>
                </div>

                <!-- Debtor Count -->
                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-amber-600">Clients Endettés</p>
                        <p class="text-2xl font-extrabold text-slate-900 mt-1" x-text="stats.debtorCount"></p>
                        <span class="text-xs text-slate-500 font-medium">Sur <span x-text="clients.length"></span> client(s) enregistrés</span>
                    </div>
                    <div class="p-3 bg-amber-50 text-amber-600 rounded-xl">
                        <i class="fa-solid fa-users-viewfinder text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Breakdown by Status Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div @click="setDebtFilter('UNPAID')" class="cursor-pointer bg-white p-4 rounded-xl border border-slate-200 hover:border-rose-400 transition shadow-sm">
                    <div class="flex justify-between items-center">
                        <span class="text-xs font-semibold text-slate-500 uppercase">Non Payées</span>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-100 text-rose-800">🔴 Unpaid</span>
                    </div>
                    <div class="mt-3 flex justify-between items-baseline">
                        <span class="text-2xl font-bold text-slate-800" x-text="stats.unpaidCount"></span>
                        <span class="text-sm font-medium text-slate-500" x-text="formatMoney(stats.unpaidAmount)"></span>
                    </div>
                </div>

                <div @click="setDebtFilter('PARTIAL')" class="cursor-pointer bg-white p-4 rounded-xl border border-slate-200 hover:border-amber-400 transition shadow-sm">
                    <div class="flex justify-between items-center">
                        <span class="text-xs font-semibold text-slate-500 uppercase">Partiellement Payées</span>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800">🟡 Partial</span>
                    </div>
                    <div class="mt-3 flex justify-between items-baseline">
                        <span class="text-2xl font-bold text-slate-800" x-text="stats.partialCount"></span>
                        <span class="text-sm font-medium text-slate-500" x-text="formatMoney(stats.partialRemainingAmount)"></span>
                    </div>
                </div>

                <div @click="setDebtFilter('OVERDUE')" class="cursor-pointer bg-white p-4 rounded-xl border border-slate-200 hover:border-red-500 transition shadow-sm">
                    <div class="flex justify-between items-center">
                        <span class="text-xs font-semibold text-slate-500 uppercase">En Retard (Critique)</span>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800 animate-pulse">⚠️ Overdue</span>
                    </div>
                    <div class="mt-3 flex justify-between items-baseline">
                        <span class="text-2xl font-bold text-red-600" x-text="stats.overdueCount"></span>
                        <span class="text-sm font-bold text-red-600" x-text="formatMoney(stats.overdueAmount)"></span>
                    </div>
                </div>

                <div @click="setDebtFilter('PAID')" class="cursor-pointer bg-white p-4 rounded-xl border border-slate-200 hover:border-emerald-400 transition shadow-sm">
                    <div class="flex justify-between items-center">
                        <span class="text-xs font-semibold text-slate-500 uppercase">Totalement Soldées</span>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">🟢 Paid</span>
                    </div>
                    <div class="mt-3 flex justify-between items-baseline">
                        <span class="text-2xl font-bold text-slate-800" x-text="stats.paidCount"></span>
                        <span class="text-sm font-medium text-emerald-600"><i class="fa-solid fa-check-double"></i> Classées</span>
                    </div>
                </div>
            </div>

            <!-- Overdue Debts Urgency Table -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mt-6">
                <div class="p-4 bg-red-50 border-b border-red-100 flex justify-between items-center">
                    <div class="flex items-center space-x-2">
                        <i class="fa-solid fa-triangle-exclamation text-red-600"></i>
                        <h3 class="font-bold text-red-900">Dettes en Retard Nécessitant Relance Immédiate</h3>
                    </div>
                    <span class="text-xs font-semibold text-red-700 bg-red-200/60 px-2 py-1 rounded" x-text="getOverdueDebts().length + ' dette(s)'"></span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-xs uppercase font-semibold text-slate-500">
                                <th class="p-3">Client</th>
                                <th class="p-3">Description</th>
                                <th class="p-3">Échéance</th>
                                <th class="p-3">Retard</th>
                                <th class="p-3 text-right">Reste Dû</th>
                                <th class="p-3 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 text-sm">
                            <template x-for="debt in getOverdueDebts()" :key="debt.id">
                                <tr class="hover:bg-red-50/30">
                                    <td class="p-3 font-semibold text-slate-900" x-text="getClientName(debt.customer_id)"></td>
                                    <td class="p-3 text-slate-600" x-text="debt.description"></td>
                                    <td class="p-3 text-red-600 font-medium" x-text="formatDate(debt.due_date)"></td>
                                    <td class="p-3">
                                        <span class="px-2 py-0.5 rounded text-xs bg-red-100 text-red-800 font-bold" x-text="getDaysOverdue(debt.due_date) + ' jour(s)'"></span>
                                    </td>
                                    <td class="p-3 text-right font-bold text-slate-900" x-text="formatMoney(getDebtRemaining(debt.id))"></td>
                                    <td class="p-3 text-center">
                                        <button @click="openPaymentModal(debt)" class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded text-xs font-medium transition">
                                            <i class="fa-solid fa-coins mr-1"></i> Enregistrer Paiement
                                        </button>
                                    </td>
                                </tr>
                            </template>
                            <template x-if="getOverdueDebts().length === 0">
                                <tr>
                                    <td colspan="6" class="p-6 text-center text-slate-400 italic">
                                        <i class="fa-solid fa-circle-check text-emerald-500 text-xl block mb-1"></i>
                                        Aucune dette en retard pour le moment ! Excellent recouvrement.
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ==================================================================== -->
        <!-- TAB 2: DEBT & PAYMENT MANAGEMENT -->
        <!-- ==================================================================== -->
        <div x-show="currentTab === 'debts'" x-cloak space-y-6>
            <!-- Controls & Filters Header -->
            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex-1 flex flex-col sm:flex-row gap-3">
                    <!-- Search input -->
                    <div class="relative flex-1">
                        <i class="fa-solid fa-magnifying-glass absolute left-3 top-3 text-slate-400"></i>
                        <input type="text" x-model="debtSearchQuery" placeholder="Rechercher client, téléphone ou boisson..." class="w-full pl-9 pr-4 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none">
                    </div>
                    <!-- Status Filter dropdown -->
                    <select x-model="statusFilter" class="border border-slate-300 rounded-lg px-3 py-2 text-sm bg-white focus:ring-2 focus:ring-brand-500 focus:outline-none">
                        <option value="ALL">Tous les statuts</option>
                        <option value="UNPAID">🔴 Non payé</option>
                        <option value="PARTIAL">🟡 Partiellement payé</option>
                        <option value="OVERDUE">⚠️ En retard</option>
                        <option value="PAID">🟢 Payé</option>
                    </select>
                    <select x-model="activeSectorFilter" class="border border-slate-300 rounded-lg px-3 py-2 text-sm bg-white focus:ring-2 focus:ring-brand-500 focus:outline-none">
                        <option value="ALL">Snack + Cave</option><option value="Snack">Snack</option><option value="Cave">Cave</option>
                    </select>
                </div>
                <div class="flex space-x-2">
                    <button @click="exportActiveDebts()" class="px-3 py-2 border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 rounded-lg text-sm font-medium transition inline-flex items-center"><i class="fa-solid fa-file-pdf mr-2"></i>Rapport PDF</button>
                    <button @click="openNewDebtModal()" class="px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-lg text-sm font-medium transition flex items-center">
                        <i class="fa-solid fa-file-circle-plus mr-2"></i> Nouvelle Facture / Dette
                    </button>
                </div>
            </div>

            <!-- Main Debts Table -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-100 border-b border-slate-200 text-xs uppercase font-semibold text-slate-600 tracking-wider">
                                <th class="p-4">Réf / Date</th>
                                <th class="p-4">Client</th>
                                <th class="p-4">Boisson / Détail</th>
                                <th class="p-4 text-right">Montant Total</th>
                                <th class="p-4 text-right">Payé</th>
                                <th class="p-4 text-right">Reste Dû</th>
                                <th class="p-4">Échéance</th>
                                <th class="p-4 text-center">Statut</th>
                                <th class="p-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 text-sm">
                            <template x-for="debt in filteredDebts" :key="debt.id">
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="p-4">
                                        <span class="font-mono text-xs text-slate-400 block" x-text="'#DET-' + String(debt.id).padStart(4, '0')"></span>
                                        <span class="text-xs text-slate-600" x-text="formatDate(debt.debt_date)"></span>
                                    </td>
                                    <td class="p-4 font-semibold text-slate-900">
                                        <div x-text="getClientName(debt.customer_id)"></div>
                                        <div class="text-xs font-normal text-slate-400" x-text="getClientPhone(debt.customer_id)"></div>
                                    </td>
                                    <td class="p-4 text-slate-700">
                                        <span x-text="debt.description"></span>
                                        <span class="ml-1 text-xs text-slate-500" x-text="'× ' + debt.quantity"></span>
                                        <p x-show="debt.notes" class="text-xs text-slate-400 italic mt-0.5" x-text="debt.notes"></p>
                                    </td>
                                    <td class="p-4 text-right font-medium text-slate-900" x-text="formatMoney(debt.amount)"></td>
                                    <td class="p-4 text-right font-medium text-emerald-600" x-text="formatMoney(getDebtPaidAmount(debt.id))"></td>
                                    <td class="p-4 text-right font-bold text-slate-900" :class="getDebtRemaining(debt.id) > 0 ? 'text-rose-600' : 'text-slate-500'" x-text="formatMoney(getDebtRemaining(debt.id))"></td>
                                    <td class="p-4 text-slate-600 text-xs">
                                        <span :class="isOverdue(debt) ? 'text-red-600 font-bold' : ''" x-text="formatDate(debt.due_date)"></span>
                                    </td>
                                    <td class="p-4 text-center">
                                        <!-- Dynamic Badge -->
                                        <span class="px-2.5 py-1 rounded-full text-xs font-bold inline-flex items-center space-x-1" :class="getStatusBadgeClass(debt)">
                                            <span x-text="getStatusIcon(debt)"></span>
                                            <span x-text="getStatusLabel(debt)"></span>
                                        </span>
                                    </td>
                                    <td class="p-4 text-right space-x-1">
                                        <button @click="exportInvoice(debt)" title="Imprimer ou enregistrer la facture en PDF" class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded text-xs font-medium transition inline-flex items-center"><i class="fa-solid fa-file-invoice mr-1"></i> Facture</button>
                                        <button x-show="!debt.archived_at && !debt.voided_at" @click="editDebt(debt)" title="Modifier cette dette" class="px-2.5 py-1.5 bg-amber-100 hover:bg-amber-200 text-amber-900 rounded text-xs font-medium transition inline-flex items-center"><i class="fa-solid fa-pen mr-1"></i> Modifier</button>
                                        <button x-show="!debt.archived_at && !debt.voided_at && getDebtPaidAmount(debt.id) === 0" @click="voidDebt(debt)" title="Supprimer cette dette de la liste active en conservant son historique" class="px-2.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded text-xs font-medium transition inline-flex items-center"><i class="fa-solid fa-trash-can mr-1"></i> Supprimer</button>
                                        <button x-show="getDebtRemaining(debt.id) > 0" @click="openPaymentModal(debt)" title="Ajouter un paiement" class="px-2.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded text-xs font-medium transition inline-flex items-center">
                                            <i class="fa-solid fa-hand-holding-dollar mr-1"></i> Payer
                                        </button>
                                        <button @click="openClientLedger(debt.customer_id)" title="Voir historique complet" class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded text-xs font-medium transition inline-flex items-center">
                                            <i class="fa-solid fa-history mr-1"></i> Détails
                                        </button>
                                    </td>
                                </tr>
                            </template>
                            <template x-if="filteredDebts.length === 0">
                                <tr>
                                    <td colspan="9" class="p-8 text-center text-slate-400">
                                        <i class="fa-solid fa-folder-open text-3xl mb-2 block"></i>
                                        Aucune dette correspondant aux critères de recherche.
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ==================================================================== -->
        <!-- TAB 3: CLIENT DIRECTORY & HISTORY -->
        <!-- ==================================================================== -->
        <div x-show="currentTab === 'clients'" x-cloak space-y-6>
            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex justify-between items-center">
                <div class="relative flex-1 max-w-md">
                    <i class="fa-solid fa-search absolute left-3 top-3 text-slate-400"></i>
                    <input type="text" x-model="clientSearchQuery" placeholder="Rechercher par nom ou numéro..." class="w-full pl-9 pr-4 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none">
                </div>
                <div class="flex flex-wrap gap-2">
                    <button @click="showArchivedClients = !showArchivedClients" class="px-3 py-2 border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 rounded-lg text-sm" x-text="showArchivedClients ? 'Voir les clients actifs' : 'Voir les clients archivés (' + archivedClientCount + ')' "></button>
                    <button @click="openModal('addClient')" class="px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-lg text-sm font-medium transition flex items-center">
                        <i class="fa-solid fa-user-plus mr-2"></i> Ajouter Client
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <template x-for="client in filteredClients" :key="client.id">
                    <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm hover:shadow transition flex flex-col justify-between">
                        <div>
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-full bg-brand-100 text-brand-700 font-bold flex items-center justify-center uppercase" x-text="client.name.substring(0, 2)"></div>
                                    <div>
                                        <h3 class="font-bold text-slate-900" x-text="client.name"></h3>
                                        <p class="text-xs text-slate-500"><i class="fa-solid fa-phone text-slate-400 mr-1"></i> <span x-text="client.phone"></span></p>
                                        <span x-show="client.archived_at" class="mt-1 inline-block rounded bg-slate-100 px-2 py-1 text-[11px] font-semibold text-slate-600">Client archivé</span>
                                    </div>
                                </div>
                            </div>
                            <div x-show="client.archived_at" class="mb-3 text-xs text-slate-600"><p x-text="'Archivé le ' + formatDateTime(client.archived_at) + ' · ' + (client.archived_by || 'Utilisateur')"></p><p class="mt-1 text-rose-700" x-text="client.archive_reason"></p></div>
                            <p class="text-xs text-slate-500 mb-4 bg-slate-50 p-2 rounded" x-text="client.notes || 'Aucune note explicative'"></p>

                            <!-- Financial summary per client -->
                            <div class="grid grid-cols-2 gap-2 text-xs border-t border-slate-100 pt-3 mb-4">
                                <div class="bg-slate-50 p-2 rounded">
                                    <span class="text-slate-400 block">Total Dettes</span>
                                    <span class="font-bold text-slate-800" x-text="formatMoney(getClientTotalDebt(client.id))"></span>
                                </div>
                                <div class="bg-rose-50 p-2 rounded">
                                    <span class="text-rose-500 block">Reste à Payer</span>
                                    <span class="font-bold text-rose-700" x-text="formatMoney(getClientRemainingDebt(client.id))"></span>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <button @click="openClientLedger(client.id)" class="w-full py-2 bg-slate-100 hover:bg-brand-50 hover:text-brand-700 text-slate-700 font-medium rounded-lg text-xs transition flex items-center justify-center">
                                <i class="fa-solid fa-receipt mr-2"></i> Voir Extrait de Compte & Historique
                            </button>
                            <button x-show="!client.archived_at" @click="archiveClient(client)" :disabled="hasClientActiveBalance(client.id)" :title="hasClientActiveBalance(client.id) ? 'Réglez ou archivez d’abord les dettes actives' : 'Retirer le client de la liste active en conservant son historique'" class="w-full py-2 rounded-lg border text-xs font-semibold transition" :class="hasClientActiveBalance(client.id) ? 'cursor-not-allowed border-slate-200 text-slate-400' : 'border-rose-200 text-rose-700 hover:bg-rose-50'"><i class="fa-solid fa-box-archive mr-2"></i>Archiver le client</button>
                            <p x-show="!client.archived_at && hasClientActiveBalance(client.id)" class="text-[11px] text-slate-500">Soldez ou archivez ses dettes avant de retirer ce client.</p>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <div x-show="currentTab === 'payments'" x-cloak class="space-y-5">
            <div class="border-b border-slate-200 pb-4"><h2 class="text-2xl font-bold text-slate-900">Paiements enregistrés</h2><p class="text-sm text-slate-500">Journal de tous les règlements associés aux dettes.</p></div>
            <div class="overflow-x-auto rounded-lg border border-slate-200 bg-white">
                <table class="w-full text-left text-sm"><thead class="bg-slate-100 text-xs uppercase text-slate-600"><tr><th class="p-3">Date</th><th class="p-3">Client</th><th class="p-3">Secteur</th><th class="p-3">Dette</th><th class="p-3">Mode</th><th class="p-3">Référence</th><th class="p-3 text-right">Montant</th><th class="p-3">Commentaire</th></tr></thead>
                    <tbody class="divide-y divide-slate-200"><template x-for="payment in orderedPayments" :key="payment.id"><tr>
                        <td class="p-3" x-text="formatDateTime(payment.payment_date)"></td><td class="p-3 font-semibold" x-text="getClientName(getDebtById(payment.debt_id)?.customer_id)"></td>
                        <td class="p-3" x-text="getDebtById(payment.debt_id)?.sector || ''"></td><td class="p-3" x-text="'#' + payment.debt_id + ' ' + (getDebtById(payment.debt_id)?.description || '')"></td>
                        <td class="p-3" x-text="payment.payment_method"></td><td class="p-3 font-mono text-xs" x-text="payment.transaction_reference || '—'"></td><td class="p-3 text-right font-semibold text-emerald-700" x-text="formatMoney(payment.amount)"></td><td class="p-3" x-text="payment.notes || '—'"></td>
                    </tr></template><template x-if="payments.length === 0"><tr><td colspan="8" class="p-8 text-center text-slate-500">Aucun paiement enregistré.</td></tr></template></tbody>
                </table>
            </div>
        </div>

        <div x-show="currentTab === 'agent'" x-cloak class="mx-auto max-w-4xl space-y-5">
            <div class="flex flex-wrap items-end justify-between gap-3 border-b border-slate-200 pb-4">
                <div><h2 class="text-2xl font-bold text-slate-900">Assistant du Complexe</h2><p class="text-sm text-slate-500">Analyse locale des dettes, du Snack et de la Cave.</p></div>
                <span class="rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-800"><i class="fa-solid fa-shield-halved mr-1"></i>Ollama local · sans téléphones</span>
            </div>
            <div class="flex flex-wrap gap-2">
                <button @click="agentInput = 'Quelles dettes sont en retard et quel est le total restant ?'" class="rounded border border-slate-300 bg-white px-3 py-2 text-xs text-slate-700 hover:bg-slate-50">Dettes en retard</button>
                <button @click="agentInput = 'Résume les dettes du Snack et de la Cave.'" class="rounded border border-slate-300 bg-white px-3 py-2 text-xs text-slate-700 hover:bg-slate-50">Résumé par secteur</button>
                <button @click="agentInput = 'Propose un message de relance pour les clients en retard.'" class="rounded border border-slate-300 bg-white px-3 py-2 text-xs text-slate-700 hover:bg-slate-50">Préparer une relance</button>
            </div>
            <section class="flex min-h-[360px] flex-col rounded-lg border border-slate-200 bg-white">
                <div class="flex-1 space-y-4 p-4" role="log" aria-live="polite" aria-label="Conversation avec l’agent IA">
                    <template x-if="agentMessages.length === 0"><p class="py-10 text-center text-sm text-slate-500">Posez une question sur les créances, demandez un résumé ou faites préparer une opération.</p></template>
                    <template x-for="(message, index) in agentMessages" :key="index">
                        <div class="max-w-[90%] rounded-lg p-3 text-sm" :class="message.role === 'user' ? 'ml-auto bg-slate-900 text-white' : 'bg-slate-100 text-slate-800'">
                            <p class="whitespace-pre-wrap" x-text="message.content"></p>
                            <div x-show="message.proposal" class="mt-3 border-t border-slate-300 pt-3">
                                <p class="mb-2 text-xs font-semibold text-slate-600" x-text="message.proposal?.type === 'create_debt' ? 'Proposition de dette · rien n’est enregistré' : message.proposal?.type === 'record_payment' ? 'Proposition de paiement · rien n’est enregistré' : 'Message de relance · aucun message envoyé'"></p>
                                <button x-show="message.proposal?.type === 'create_debt' || message.proposal?.type === 'record_payment'" @click="prepareAgentAction(message.proposal)" class="rounded bg-emerald-700 px-3 py-2 text-xs font-semibold text-white hover:bg-emerald-800">Vérifier et ouvrir le formulaire</button>
                                <button x-show="message.proposal?.type === 'reminder'" @click="copyAgentReminder(message.proposal.reminder_message)" class="rounded bg-slate-800 px-3 py-2 text-xs font-semibold text-white hover:bg-slate-700">Copier la relance</button>
                            </div>
                        </div>
                    </template>
                    <p x-show="agentBusy" class="text-sm text-slate-500" role="status">L’assistant analyse les données...</p>
                    <p x-show="agentStatus" x-text="agentStatus" class="text-xs text-emerald-700" role="status"></p>
                    <p x-show="agentError" x-text="agentError" class="rounded border border-amber-200 bg-amber-50 p-3 text-sm text-amber-900" role="alert"></p>
                </div>
                <form @submit.prevent="sendAgentMessage()" class="flex flex-col gap-2 border-t border-slate-200 p-3 sm:flex-row">
                    <textarea x-model="agentInput" rows="2" maxlength="2000" required placeholder="Ex. Quel est le reste dû du Snack ?" class="min-h-12 flex-1 resize-y rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-emerald-600 focus:outline-none"></textarea>
                    <button type="submit" :disabled="agentBusy" class="self-end rounded-md bg-emerald-700 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-800 disabled:opacity-50"><i class="fa-solid fa-paper-plane mr-2"></i>Envoyer</button>
                </form>
            </section>
            <p class="text-xs text-slate-500">Toute dette ou paiement proposé doit être vérifié et validé dans son formulaire. Les relances sont copiées uniquement ; l’agent n’envoie rien.</p>
        </div>

        <div x-show="currentTab === 'history'" x-cloak class="space-y-5">
            <div class="flex flex-col gap-3 border-b border-slate-200 pb-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900">Historique des dettes</h2>
                    <p class="text-sm text-slate-500">Toutes les dettes, y compris celles archivées ou entièrement réglées.</p>
                </div>
                <button @click="exportHistory()" class="px-4 py-2 bg-slate-900 hover:bg-slate-700 text-white rounded-lg text-sm font-medium"><i class="fa-solid fa-file-pdf mr-2"></i>Exporter en PDF</button>
            </div>
            <div class="grid grid-cols-1 gap-3 rounded-lg border border-slate-200 bg-white p-4 sm:grid-cols-2 lg:grid-cols-3">
                <input type="search" x-model="historySearch" placeholder="Rechercher un client" class="rounded-md border border-slate-300 px-3 py-2 text-sm">
                <select x-model="historySector" class="rounded-md border border-slate-300 bg-white px-3 py-2 text-sm">
                    <option value="ALL">Tous les secteurs</option><option value="Snack">Snack</option><option value="Cave">Cave</option>
                </select>
                <select x-model="historyStatus" class="rounded-md border border-slate-300 bg-white px-3 py-2 text-sm">
                    <option value="ALL">Tous les statuts</option><option value="UNPAID">Non payé</option><option value="PARTIAL">Partiellement payé</option><option value="PAID">Payé</option><option value="OVERDUE">En retard</option><option value="VOID">Annulée</option>
                </select>
                <label class="text-xs text-slate-500">Du<input type="date" x-model="historyStart" class="mt-1 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm text-slate-800"></label>
                <label class="text-xs text-slate-500">Au<input type="date" x-model="historyEnd" class="mt-1 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm text-slate-800"></label>
                <input type="number" min="0" step="0.01" x-model="historyAmount" placeholder="Montant exact (FCFA)" class="rounded-md border border-slate-300 px-3 py-2 text-sm">
            </div>
            <div class="overflow-x-auto rounded-lg border border-slate-200 bg-white">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-100 text-xs uppercase text-slate-600"><tr><th class="p-3">Client</th><th class="p-3">Secteur</th><th class="p-3">Dette</th><th class="p-3 text-right">Payé</th><th class="p-3 text-right">Reste</th><th class="p-3">Statut</th><th class="p-3">Date</th><th class="p-3"></th></tr></thead>
                    <tbody class="divide-y divide-slate-200">
                        <template x-for="debt in historyDebts" :key="debt.id"><tr class="hover:bg-slate-50">
                            <td class="p-3"><button @click="openClientLedger(debt.customer_id)" class="font-semibold text-brand-700 hover:underline" x-text="getClientName(debt.customer_id)"></button></td>
                            <td class="p-3" x-text="debt.sector"></td><td class="p-3" x-text="formatMoney(debt.amount)"></td>
                            <td class="p-3 text-right text-emerald-700" x-text="formatMoney(getDebtPaidAmount(debt.id))"></td>
                            <td class="p-3 text-right font-semibold" x-text="formatMoney(getDebtRemaining(debt.id))"></td>
                            <td class="p-3"><span class="rounded-full px-2 py-1 text-xs font-bold" :class="getStatusBadgeClass(debt)" x-text="getStatusLabel(debt)"></span><span x-show="debt.archived_at" class="ml-1 text-xs text-slate-400">Archivée</span><p x-show="debt.voided_at" class="mt-1 max-w-48 text-xs text-rose-700" x-text="debt.void_reason"></p><p x-show="debt.voided_at" class="mt-1 text-[11px] text-slate-500" x-text="'Annulée le ' + formatDateTime(debt.voided_at) + ' · ' + (debt.voided_by || 'Utilisateur')"></p></td>
                            <td class="p-3" x-text="formatDate(debt.debt_date)"></td><td class="p-3 whitespace-nowrap">
                                <button @click="exportInvoice(debt)" class="mr-2 text-slate-500 hover:text-brand-700" title="Imprimer la facture"><i class="fa-solid fa-file-invoice"></i></button>
                                <button x-show="!debt.archived_at && !debt.voided_at" @click="editDebt(debt)" class="mr-2 text-amber-700 hover:text-amber-900" title="Modifier cette dette"><i class="fa-solid fa-pen"></i></button>
                                <button @click="openClientLedger(debt.customer_id)" class="text-slate-500 hover:text-brand-700" title="Historique du client"><i class="fa-solid fa-arrow-up-right-from-square"></i></button>
                            </td>
                        </tr></template>
                        <template x-if="historyDebts.length === 0"><tr><td colspan="8" class="p-8 text-center text-slate-500">Aucun résultat pour ces filtres.</td></tr></template>
                    </tbody>
                </table>
            </div>
        </div>

        <div x-show="currentTab === 'admin'" x-cloak class="mx-auto max-w-3xl space-y-5">
            <div class="border-b border-slate-200 pb-4"><h2 class="text-2xl font-bold text-slate-900">Administration</h2><p class="text-sm text-slate-500">Archivage sécurisé des dettes actives par secteur.</p></div>
            <section class="space-y-4 rounded-lg border border-slate-200 bg-white p-5">
                <h3 class="font-bold text-slate-900">Accès administrateur</h3>
                <form x-show="!isAdmin" @submit.prevent="adminLogin()" class="flex flex-col gap-2 sm:flex-row">
                    <input type="password" x-model="adminPassword" autocomplete="current-password" required placeholder="Mot de passe administrateur" class="min-w-0 flex-1 rounded-md border border-slate-300 px-3 py-2 text-sm">
                    <button class="rounded-md bg-slate-900 px-4 py-2 text-sm font-semibold text-white">Se connecter</button>
                </form>
                <p x-show="isAdmin" class="text-sm font-medium text-emerald-700">Session administrateur active.</p>
                <button x-show="isAdmin" @click="adminLogout()" class="text-sm font-semibold text-slate-600 underline">Fermer la session administrateur</button>
                <p x-show="adminMessage" x-text="adminMessage" class="text-sm text-rose-700" role="alert"></p>
            </section>
            <section class="space-y-4 rounded-lg border border-rose-200 bg-white p-5">
                <h3 class="font-bold text-rose-800">Réinitialiser les dettes actives</h3>
                <p class="text-sm text-slate-600">Les dettes et leurs paiements seront archivés, jamais supprimés. Elles resteront visibles dans l’historique et les rapports.</p>
                <div class="flex flex-col gap-3 sm:flex-row">
                    <select x-model="resetSector" class="flex-1 rounded-md border border-slate-300 bg-white px-3 py-2 text-sm"><option value="Snack">Snack</option><option value="Cave">Cave</option><option value="Tous">Snack + Cave</option></select>
                    <button @click="resetDebts()" :disabled="!isAdmin" class="rounded-md bg-rose-700 px-4 py-2 text-sm font-semibold text-white disabled:cursor-not-allowed disabled:bg-slate-300"><i class="fa-solid fa-box-archive mr-2"></i>Archiver et réinitialiser</button>
                </div>
            </section>
            <section class="overflow-x-auto rounded-lg border border-slate-200 bg-white">
                <h3 class="border-b border-slate-200 p-4 font-bold text-slate-900">Archives de réinitialisation</h3>
                <table class="w-full text-left text-sm"><thead class="bg-slate-100 text-xs uppercase text-slate-600"><tr><th class="p-3">Date</th><th class="p-3">Secteur</th><th class="p-3">Dettes archivées</th><th class="p-3">Total avant</th><th class="p-3">Utilisateur</th></tr></thead>
                    <tbody class="divide-y divide-slate-200"><template x-for="archive in resetHistory" :key="archive.id"><tr><td class="p-3" x-text="formatDateTime(archive.created_at)"></td><td class="p-3" x-text="archive.sector"></td><td class="p-3" x-text="archive.debt_count"></td><td class="p-3" x-text="formatMoney(archive.total_debt)"></td><td class="p-3" x-text="archive.performed_by"></td></tr></template>
                        <template x-if="resetHistory.length === 0"><tr><td colspan="5" class="p-5 text-center text-slate-500">Aucune réinitialisation archivée.</td></tr></template></tbody>
                </table>
            </section>
        </div>

        <section id="print-report" class="hidden print:block">
            <h1>COMPLEXE SAINT JOSEPH</h1><h2 x-text="invoiceDebt ? (invoiceDebt.voided_at ? 'FACTURE ANNULÉE' : 'FACTURE DE DETTE') : (reportClientId ? 'HISTORIQUE CLIENT — ' + getClientName(reportClientId) : (reportMode === 'active' ? 'RAPPORT — DETTES ACTIVES' : 'RAPPORT — HISTORIQUE DES DETTES'))"></h2>
            <section x-show="invoiceDebt">
                <p><strong>Facture :</strong> <span x-text="invoiceDebt ? '#FAC-' + String(invoiceDebt.id).padStart(5, '0') : ''"></span></p>
                <p><strong>Client :</strong> <span x-text="invoiceDebt ? getClientName(invoiceDebt.customer_id) : ''"></span> · <span x-text="invoiceDebt ? (getClientPhone(invoiceDebt.customer_id) || 'Téléphone non renseigné') : ''"></span></p>
                <p><strong>Secteur :</strong> <span x-text="invoiceDebt?.sector"></span> · <strong>Date :</strong> <span x-text="invoiceDebt ? formatDate(invoiceDebt.debt_date) : ''"></span> · <strong>Échéance :</strong> <span x-text="invoiceDebt ? formatDate(invoiceDebt.due_date) : ''"></span></p>
                <table><thead><tr><th>Produit / boisson</th><th>Quantité</th><th>Montant total</th></tr></thead><tbody><tr><td x-text="invoiceDebt?.description"></td><td x-text="invoiceDebt?.quantity"></td><td x-text="invoiceDebt ? formatMoney(invoiceDebt.amount) : ''"></td></tr></tbody></table>
                <p><strong>Total :</strong> <span x-text="invoiceDebt ? formatMoney(invoiceDebt.amount) : ''"></span></p>
                <p><strong>Total payé :</strong> <span x-text="invoiceDebt ? formatMoney(getDebtPaidAmount(invoiceDebt.id)) : ''"></span> · <strong>Reste à payer :</strong> <span x-text="invoiceDebt ? formatMoney(getDebtRemaining(invoiceDebt.id)) : ''"></span></p>
                <p><strong>Statut :</strong> <span x-text="invoiceDebt ? getStatusLabel(invoiceDebt) : ''"></span></p>
                <p x-show="invoiceDebt?.voided_at"><strong>Motif d’annulation :</strong> <span x-text="invoiceDebt?.void_reason"></span></p>
                <p x-show="invoiceDebt?.notes"><strong>Commentaire :</strong> <span x-text="invoiceDebt?.notes"></span></p>
                <p x-text="'Éditée le : ' + new Date().toLocaleString('fr-FR')"></p>
            </section>
            <section x-show="!invoiceDebt">
                <p x-text="'Période : ' + (reportMode === 'active' ? 'Toutes dates (dettes actives)' : (historyStart || 'Toutes dates') + ' → ' + (historyEnd || 'Toutes dates'))"></p>
                <p x-text="'Secteur : ' + ((reportMode === 'active' ? activeSectorFilter : historySector) === 'ALL' ? 'Tous' : (reportMode === 'active' ? activeSectorFilter : historySector))"></p>
                <p x-show="reportMode === 'active'" x-text="'Statut filtré : ' + (statusFilter === 'ALL' ? 'Tous' : statusFilter)"></p>
                <table><thead><tr><th>Client</th><th>Secteur</th><th>Dette</th><th>Payé</th><th>Reste</th><th>Statut</th><th>Date</th><th>Motif d’annulation</th></tr></thead>
                    <tbody><template x-for="debt in reportDebts" :key="'print-' + debt.id"><tr><td x-text="getClientName(debt.customer_id)"></td><td x-text="debt.sector"></td><td x-text="formatMoney(debt.amount)"></td><td x-text="formatMoney(getDebtPaidAmount(debt.id))"></td><td x-text="formatMoney(getDebtRemaining(debt.id))"></td><td x-text="getStatusLabel(debt)"></td><td x-text="formatDate(debt.debt_date)"></td><td x-text="debt.void_reason || '—'"></td></tr></template></tbody>
                </table>
                <p x-text="'Total des dettes : ' + formatMoney(reportTotals.debt)"></p><p x-text="'Total payé : ' + formatMoney(reportTotals.paid)"></p><p x-text="'Total restant : ' + formatMoney(reportTotals.remaining)"></p>
                <p x-text="'Nombre de clients concernés : ' + reportTotals.customers"></p><p x-text="'Généré le : ' + new Date().toLocaleString('fr-FR')"></p>
                <template x-if="reportClientId"><h3>Journal des opérations</h3></template>
                <table x-show="reportClientId"><thead><tr><th>Date</th><th>Opération</th><th>Montant</th><th>Reste</th></tr></thead><tbody>
                    <template x-for="(operation, index) in getClientOperations(reportClientId)" :key="'print-operation-' + index"><tr><td x-text="formatDateTime(operation.date)"></td><td x-text="operation.label"></td><td x-text="formatMoney(operation.amount)"></td><td x-text="formatMoney(operation.remaining)"></td></tr></template>
                </tbody></table>
            </section>
        </section>

        <!-- ==================================================================== -->
        <!-- TAB 4: ARCHITECTURE & TECHNICAL SPECIFICATIONS -->
        <!-- ==================================================================== -->
        <div x-show="currentTab === 'architecture'" x-cloak space-y-8 class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm text-slate-700 leading-relaxed">
            
            <div class="border-b border-slate-200 pb-4">
                <h2 class="text-2xl font-bold text-slate-900 flex items-center">
                    <i class="fa-solid fa-layer-group text-amber-500 mr-3"></i> Architecture Technico-Fonctionnelle du Module
                </h2>
                <p class="text-sm text-slate-500 mt-1">Spécifications pour l'intégration propre dans un ERP / CRM basé sur PHP 8.x et MySQL 8.x/MariaDB.</p>
            </div>

            <!-- 1. Architecture du module -->
            <section class="space-y-3">
                <h3 class="text-lg font-bold text-slate-900 border-l-4 border-amber-500 pl-3">1. Architecture et Principes Modulaires</h3>
                <p class="text-sm">
                    Le module repose sur le patron d'architecture <strong>MVC (Modèle-Vue-Contrôleur)</strong> afin de garantir un découplage total entre l'interface utilisateur, la logique métier financière et les interactions avec la base de données.
                </p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs font-mono mt-2">
                    <div class="p-3 bg-slate-50 border border-slate-200 rounded-lg">
                        <span class="font-bold text-amber-700 block mb-1">💼 Modèles (Data & SQL)</span>
                        Contient la couche d'accès aux données (PDO préparé), gère la persistance et valide l'intégrité référentielle des données financières.
                    </div>
                    <div class="p-3 bg-slate-50 border border-slate-200 rounded-lg">
                        <span class="font-bold text-brand-700 block mb-1">⚙️ Services (Logique Métier)</span>
                        Encapsule les calculs solde ($Reste$), les transitions d'état dynamiques et le contrôle strict anti-surpaiement dans des transactions SQL.
                    </div>
                    <div class="p-3 bg-slate-50 border border-slate-200 rounded-lg">
                        <span class="font-bold text-emerald-700 block mb-1">🎨 Vues & Contrôleurs (API/UI)</span>
                        Restitue les tableaux de bord interactifs (AJAX/JSON) et expose des endpoints REST sécurisés avec vérification de jeton CSRF.
                    </div>
                </div>
            </section>

            <!-- 2. Tables & Data Dictionary -->
            <section class="space-y-4">
                <h3 class="text-lg font-bold text-slate-900 border-l-4 border-amber-500 pl-3">2. Schéma Relationnel de la Base de Données (MCD / SQL)</h3>
                <p class="text-sm">
                    Afin d'éviter l'anti-pattern d'un simple champ booléen `paid = true/false`, le schéma repose sur 3 tables normalisées en <strong>3ème Forme Normale (3NF)</strong> avec gestion fine de la traçabilité des règlements.
                </p>

                <!-- Tables Details -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-slate-100 border-b border-slate-300 font-bold uppercase text-slate-600">
                                <th class="p-2.5">Table</th>
                                <th class="p-2.5">Champ</th>
                                <th class="p-2.5">Type SQL</th>
                                <th class="p-2.5">Contraintes</th>
                                <th class="p-2.5">Rôle / Description</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 font-mono">
                            <!-- Customers -->
                            <tr class="bg-amber-50/30">
                                <td class="p-2 font-bold text-amber-900" rowspan="4">customers</td>
                                <td class="p-2 text-indigo-600">id</td>
                                <td class="p-2">BIGINT UNSIGNED</td>
                                <td class="p-2 text-rose-600">PRIMARY KEY, AUTO_INC</td>
                                <td class="p-2 font-sans text-slate-600">Identifiant unique du client</td>
                            </tr>
                            <tr class="bg-amber-50/30">
                                <td class="p-2">name</td>
                                <td class="p-2">VARCHAR(150)</td>
                                <td class="p-2 text-rose-600">NOT NULL</td>
                                <td class="p-2 font-sans text-slate-600">Nom complet ou raison sociale</td>
                            </tr>
                            <tr class="bg-amber-50/30">
                                <td class="p-2">phone</td>
                                <td class="p-2">VARCHAR(30)</td>
                                <td class="p-2 text-amber-600">INDEX</td>
                                <td class="p-2 font-sans text-slate-600">Numéro de téléphone principal</td>
                            </tr>
                            <tr class="bg-amber-50/30">
                                <td class="p-2">created_at</td>
                                <td class="p-2">DATETIME</td>
                                <td class="p-2">DEFAULT CURRENT_TIMESTAMP</td>
                                <td class="p-2 font-sans text-slate-600">Horodatage de création</td>
                            </tr>

                            <!-- Debts -->
                            <tr class="bg-blue-50/30">
                                <td class="p-2 font-bold text-blue-900" rowspan="6">debts</td>
                                <td class="p-2 text-indigo-600">id</td>
                                <td class="p-2">BIGINT UNSIGNED</td>
                                <td class="p-2 text-rose-600">PRIMARY KEY, AUTO_INC</td>
                                <td class="p-2 font-sans text-slate-600">Identifiant unique de la créance</td>
                            </tr>
                            <tr class="bg-blue-50/30">
                                <td class="p-2 text-amber-600">customer_id</td>
                                <td class="p-2">BIGINT UNSIGNED</td>
                                <td class="p-2 text-rose-600">FOREIGN KEY (RESTRICT)</td>
                                <td class="p-2 font-sans text-slate-600">Rattaché à customers.id</td>
                            </tr>
                            <tr class="bg-blue-50/30">
                                <td class="p-2">amount</td>
                                <td class="p-2">DECIMAL(12,2)</td>
                                <td class="p-2 text-rose-600">NOT NULL, CHECK (amount > 0)</td>
                                <td class="p-2 font-sans text-slate-600">Montant initial de la dette (ex: 150000.00 FCFA)</td>
                            </tr>
                            <tr class="bg-blue-50/30">
                                <td class="p-2">debt_date</td>
                                <td class="p-2">DATE</td>
                                <td class="p-2 text-rose-600">NOT NULL</td>
                                <td class="p-2 font-sans text-slate-600">Date de contraction de la dette</td>
                            </tr>
                            <tr class="bg-blue-50/30">
                                <td class="p-2">due_date</td>
                                <td class="p-2">DATE</td>
                                <td class="p-2 text-rose-600">NOT NULL, INDEX</td>
                                <td class="p-2 font-sans text-slate-600">Date limite / Échéance accordée</td>
                            </tr>
                            <tr class="bg-blue-50/30">
                                <td class="p-2">description</td>
                                <td class="p-2">VARCHAR(255)</td>
                                <td class="p-2 text-rose-600">NOT NULL</td>
                                <td class="p-2 font-sans text-slate-600">Libellé du produit / service vendu</td>
                            </tr>

                            <!-- Payments -->
                            <tr class="bg-emerald-50/30">
                                <td class="p-2 font-bold text-emerald-900" rowspan="5">payments</td>
                                <td class="p-2 text-indigo-600">id</td>
                                <td class="p-2">BIGINT UNSIGNED</td>
                                <td class="p-2 text-rose-600">PRIMARY KEY, AUTO_INC</td>
                                <td class="p-2 font-sans text-slate-600">Identifiant du paiement</td>
                            </tr>
                            <tr class="bg-emerald-50/30">
                                <td class="p-2 text-amber-600">debt_id</td>
                                <td class="p-2">BIGINT UNSIGNED</td>
                                <td class="p-2 text-rose-600">FOREIGN KEY (CASCADE)</td>
                                <td class="p-2 font-sans text-slate-600">Rattaché à debts.id</td>
                            </tr>
                            <tr class="bg-emerald-50/30">
                                <td class="p-2">amount</td>
                                <td class="p-2">DECIMAL(12,2)</td>
                                <td class="p-2 text-rose-600">NOT NULL, CHECK (amount > 0)</td>
                                <td class="p-2 font-sans text-slate-600">Montant versé lors de ce versement</td>
                            </tr>
                            <tr class="bg-emerald-50/30">
                                <td class="p-2">payment_date</td>
                                <td class="p-2">DATETIME</td>
                                <td class="p-2 text-rose-600">NOT NULL</td>
                                <td class="p-2 font-sans text-slate-600">Date & heure effectives de l'encaissement</td>
                            </tr>
                            <tr class="bg-emerald-50/30">
                                <td class="p-2">payment_method</td>
                                <td class="p-2">ENUM(...)</td>
                                <td class="p-2">'Espèces', 'Mobile Money', etc.</td>
                                <td class="p-2 font-sans text-slate-600">Canal de règlement utilisé</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- 3. Math & Business Logic Rules -->
            <section class="space-y-3">
                <h3 class="text-lg font-bold text-slate-900 border-l-4 border-amber-500 pl-3">3. Règles Métier & Logique du Calcul de Solde</h3>
                <p class="text-sm">
                    Le calcul du solde restant dû $R$ pour une dette $i$ s'effectue dynamiquement par agrégation SQL afin d'éliminer toute désynchronisation :
                </p>
                <div class="bg-slate-900 text-amber-300 p-4 rounded-lg font-mono text-xs overflow-x-auto">
                    $$R = D_{initiale} - \sum_{k=1}^{n} P_k$$
                    <p class="text-slate-400 mt-2">Où $D_{initiale}$ est le montant total de la dette, et $P_k$ représente chaque acompte partiel effectué.</p>
                </div>

                <div class="mt-3">
                    <h4 class="font-bold text-slate-800 text-sm mb-2">Automate d'États (Statuts Échéancier) :</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                        <div class="p-3 border border-slate-200 rounded-lg bg-rose-50/50">
                            <span class="font-bold text-rose-800">🔴 Non payé (`UNPAID`)</span>
                            <p class="text-slate-600 mt-1">Si $\sum P_k = 0$ et $Date_{actuelle} \le Date_{échéance}$. Aucune somme perçue.</p>
                        </div>
                        <div class="p-3 border border-slate-200 rounded-lg bg-amber-50/50">
                            <span class="font-bold text-amber-800">🟡 Partiellement payé (`PARTIAL`)</span>
                            <p class="text-slate-600 mt-1">Si $0 < \sum P_k < D_{initiale}$ et $Date_{actuelle} \le Date_{échéance}$. Acompte enregistré.</p>
                        </div>
                        <div class="p-3 border border-slate-200 rounded-lg bg-red-50/50">
                            <span class="font-bold text-red-800">⚠️ En retard (`OVERDUE`)</span>
                            <p class="text-slate-600 mt-1">Si $R > 0$ ET $Date_{actuelle} > Date_{échéance}$. Priorité de relance maximale.</p>
                        </div>
                        <div class="p-3 border border-slate-200 rounded-lg bg-emerald-50/50">
                            <span class="font-bold text-emerald-800">🟢 Payé (`PAID`)</span>
                            <p class="text-slate-600 mt-1">Si $R \le 0$ ($\sum P_k = D_{initiale}$). Créance éteinte et archivée.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 4. Security & Best Practices -->
            <section class="space-y-3">
                <h3 class="text-lg font-bold text-slate-900 border-l-4 border-amber-500 pl-3">4. Règles Strictes de Sécurité & Intégrité PHP / MySQL</h3>
                <ul class="list-disc list-inside text-sm space-y-2 text-slate-600">
                    <li><strong>Contrôle strict du surpaiement :</strong> Avant d'insérer tout paiement $P$, le serveur vérouille en ligne la dette (`SELECT amount FROM debts WHERE id = ? FOR UPDATE`) et vérifie que $P \le R_{restant}$. Tout dépassement déclenche une exception de sécurité (`422 Unprocessable Entity`).</li>
                    <li><strong>Isolation par Transactions SQL (`BEGIN...COMMIT`) :</strong> La création de paiement est enveloppée dans une transaction PDO. En cas d'erreur réseau ou d'échec de validation, un `ROLLBACK` strict est appliqué.</li>
                    <li><strong>Protection Injections SQL & XSS :</strong> Préparation absolue de toutes les requêtes via PDO (`PDO::PREPARE`) et nettoyage systématique des sorties avec `htmlspecialchars()`.</li>
                    <li><strong>Intégrité Référentielle (`RESTRICT`) :</strong> Empêche la suppression accidentelle d'un client dans `customers` s'il possède au moins une dette non soldée dans la table `debts`.</li>
                </ul>
            </section>

            <!-- 5. Production SQL Schema & PHP Class -->
            <section class="space-y-3">
                <h3 class="text-lg font-bold text-slate-900 border-l-4 border-amber-500 pl-3">5. Script SQL & Classe PHP PDO Prêts pour la Production</h3>
                <p class="text-sm">Vous pouvez copier-coller directement cette structure SQL et ce gestionnaire PHP dans votre projet :</p>
                
                <div class="space-y-4">
                    <!-- SQL Code -->
                    <div>
                        <span class="text-xs font-bold text-slate-500 block mb-1"><i class="fa-solid fa-database"></i> Script MySQL Schema (schema.sql)</span>
                        <pre class="bg-slate-900 text-slate-100 p-4 rounded-lg font-mono text-xs overflow-x-auto custom-scrollbar"><code>CREATE TABLE IF NOT EXISTS `customers` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(150) NOT NULL,
  `phone` VARCHAR(30) NULL,
  `notes` TEXT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_customers_phone` (`phone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `debts` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `customer_id` BIGINT UNSIGNED NOT NULL,
  `description` VARCHAR(255) NOT NULL,
  `amount` DECIMAL(12,2) NOT NULL,
  `debt_date` DATE NOT NULL,
  `due_date` DATE NOT NULL,
  `notes` TEXT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_debts_customer` FOREIGN KEY (`customer_id`) 
    REFERENCES `customers` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `chk_debt_amount_positive` CHECK (`amount` > 0),
  INDEX `idx_debts_due_date` (`due_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `payments` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `debt_id` BIGINT UNSIGNED NOT NULL,
  `amount` DECIMAL(12,2) NOT NULL,
  `payment_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `payment_method` VARCHAR(40) NOT NULL,
    `transaction_reference` VARCHAR(100) NULL,
  `notes` TEXT NULL,
  CONSTRAINT `fk_payments_debt` FOREIGN KEY (`debt_id`) 
    REFERENCES `debts` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `chk_payment_amount_positive` CHECK (`amount` > 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;</code></pre>
                    </div>

                    <!-- PHP Code -->
                    <div>
                        <span class="text-xs font-bold text-slate-500 block mb-1"><i class="fa-brands fa-php"></i> Services Métier PHP (DebtManager.php)</span>
                        <pre class="bg-slate-900 text-slate-100 p-4 rounded-lg font-mono text-xs overflow-x-auto custom-scrollbar"><code>&lt;?php
declare(strict_types=1);

class DebtManager {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Enregistre un versement partiel ou total avec verrouillage transactionnel anti-surpaiement
     */
    public function recordPayment(int $debtId, float $amount, string $method, string $notes = ''): bool {
        if ($amount &lt;= 0) {
            throw new InvalidArgumentException("Le montant du paiement doit être supérieur à zéro.");
        }

        $this-&gt;pdo-&gt;beginTransaction();

        try {
            // 1. Verrouiller la dette pour éviter la concurrence
            $stmt = $this-&gt;pdo-&gt;prepare("SELECT amount FROM debts WHERE id = :id FOR UPDATE");
            $stmt-&gt;execute(['id' =&gt; $debtId]);
            $debt = $stmt-&gt;fetch(PDO::FETCH_ASSOC);

            if (!$debt) {
                throw new Exception("Créance introuvable.");
            }

            // 2. Calculer le cumule déjà payé
            $stmtPaid = $this-&gt;pdo-&gt;prepare("SELECT COALESCE(SUM(amount), 0) as total_paid FROM payments WHERE debt_id = :debt_id");
            $stmtPaid-&gt;execute(['debt_id' =&gt; $debtId]);
            $alreadyPaid = (float) $stmtPaid-&gt;fetchColumn();

            $totalDebt = (float) $debt['amount'];
            $remaining = $totalDebt - $alreadyPaid;

            // 3. Empêcher le surpaiement
            if (round($amount, 2) &gt; round($remaining, 2)) {
                throw new Exception("Le paiement (" . number_format($amount, 2) . ") dépasse le solde restant dû (" . number_format($remaining, 2) . ").");
            }

            // 4. Inscription du paiement
            $stmtInsert = $this-&gt;pdo-&gt;prepare("
                INSERT INTO payments (debt_id, amount, payment_date, payment_method, notes)
                VALUES (:debt_id, :amount, NOW(), :method, :notes)
            ");
            $stmtInsert-&gt;execute([
                'debt_id' =&gt; $debtId,
                'amount' =&gt; $amount,
                'method' =&gt; $method,
                'notes' =&gt; $notes
            ]);

            $this-&gt;pdo-&gt;commit();
            return true;
        } catch (Throwable $e) {
            $this-&gt;pdo-&gt;rollBack();
            throw $e;
        }
    }

    /**
     * Récupère la synthèse complète des dettes avec le reste dû et le statut dynamique
     */
    public function getDebtsOverview(): array {
        $sql = "
            SELECT 
                d.*,
                c.name as customer_name,
                c.phone as customer_phone,
                COALESCE(SUM(p.amount), 0) as total_paid,
                (d.amount - COALESCE(SUM(p.amount), 0)) as remaining_balance,
                CASE 
                    WHEN COALESCE(SUM(p.amount), 0) &gt;= d.amount THEN 'PAID'
                    WHEN CURDATE() &gt; d.due_date AND COALESCE(SUM(p.amount), 0) &lt; d.amount THEN 'OVERDUE'
                    WHEN COALESCE(SUM(p.amount), 0) &gt; 0 THEN 'PARTIAL'
                    ELSE 'UNPAID'
                END as status
            FROM debts d
            JOIN customers c ON d.customer_id = c.id
            LEFT JOIN payments p ON d.id = p.debt_id
            GROUP BY d.id
            ORDER BY d.created_at DESC
        ";
        return $this-&gt;pdo-&gt;query($sql)-&gt;fetchAll(PDO::FETCH_ASSOC);
    }
}</code></pre>
                    </div>
                </div>
            </section>
        </div>

    </main>

    <!-- ==================================================================== -->
    <!-- MODALS SECTION (Dynamic Alpine.js) -->
    <!-- ==================================================================== -->

    <!-- Modal 1: Add Customer -->
    <div x-show="modals.addClient" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div @click.away="closeModal('addClient')" class="bg-white rounded-xl shadow-xl max-w-md w-full p-6 space-y-4">
            <div class="flex justify-between items-center border-b pb-3">
                <h3 class="font-bold text-slate-900 text-lg"><i class="fa-solid fa-user-plus text-brand-600 mr-2"></i> Nouveau Client</h3>
                <button @click="closeModal('addClient')" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>
            <form @submit.prevent="submitAddClient()" class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nom complet / Raison sociale *</label>
                    <input type="text" x-model="forms.client.name" required class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none" placeholder="Ex: Jean Dupont ou Boutique ABC">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Téléphone (facultatif)</label>
                    <input type="tel" x-model="forms.client.phone" class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none" placeholder="Ex: +237 690 00 00 00">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Note / Adresse / Info utile</label>
                    <textarea x-model="forms.client.notes" rows="2" class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none" placeholder="Informations complémentaires..."></textarea>
                </div>
                <div class="flex justify-end space-x-2 pt-3 border-t">
                    <button type="button" @click="closeModal('addClient')" class="px-4 py-2 border rounded-lg text-sm font-medium text-slate-600 hover:bg-slate-50">Annuler</button>
                    <button type="submit" class="px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-lg text-sm font-medium">Enregistrer Client</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal 2: Add Debt -->
    <div x-show="modals.addDebt" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div @click.away="closeModal('addDebt')" class="bg-white rounded-xl shadow-xl max-w-lg w-full p-6 space-y-4">
            <div class="flex justify-between items-center border-b pb-3">
                <h3 class="font-bold text-slate-900 text-lg"><i class="fa-solid fa-file-invoice-dollar text-brand-600 mr-2"></i><span x-text="editingDebtId ? 'Modifier la facture / dette' : 'Créer une facture / dette'"></span></h3>
                <button @click="closeModal('addDebt')" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>
            <form @submit.prevent="submitSaveDebt()" class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Sélectionner le Client *</label>
                    <select x-model="forms.debt.customer_id" required class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none">
                        <option value="">-- Choisir un client --</option>
                        <template x-for="c in activeClients" :key="c.id">
                            <option :value="c.id" x-text="c.name + ' (' + c.phone + ')'"></option>
                        </template>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Produit / boisson *</label>
                    <input type="text" list="drink-products" x-model="forms.debt.description" required class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none" placeholder="Ex. bière, vin rouge, jus d'orange">
                    <datalist id="drink-products"><option value="Vin rouge"></option><option value="Vin blanc"></option><option value="Bière"></option><option value="Jus"></option><option value="Eau minérale"></option><option value="Soda"></option><option value="Autre boisson"></option></datalist>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Secteur *</label>
                        <select x-model="forms.debt.sector" required class="w-full px-3 py-2 border rounded-lg text-sm"><option value="Snack">Snack</option><option value="Cave">Cave</option></select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Quantité *</label>
                        <input type="number" min="0.001" step="0.001" x-model="forms.debt.quantity" required class="w-full px-3 py-2 border rounded-lg text-sm">
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Montant total (FCFA) *</label>
                        <input type="number" min="1" step="0.01" x-model="forms.debt.amount" required class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none" placeholder="Ex: 50000">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Date de la dette *</label>
                        <input type="date" x-model="forms.debt.debt_date" required class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Date d'Échéance *</label>
                    <input type="date" x-model="forms.debt.due_date" required class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Commentaire / Conditions</label>
                    <textarea x-model="forms.debt.notes" rows="2" class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none" placeholder="Remarques éventuelles..."></textarea>
                </div>
                <div class="flex justify-end space-x-2 pt-3 border-t">
                    <button type="button" @click="closeModal('addDebt')" class="px-4 py-2 border rounded-lg text-sm font-medium text-slate-600 hover:bg-slate-50">Annuler</button>
                    <button type="submit" class="px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-lg text-sm font-medium" x-text="editingDebtId ? 'Enregistrer les modifications' : 'Créer la facture / dette'"></button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal 3: Add Payment -->
    <div x-show="modals.addPayment" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div @click.away="closeModal('addPayment')" class="bg-white rounded-xl shadow-xl max-w-md w-full p-6 space-y-4">
            <div class="flex justify-between items-center border-b pb-3">
                <div>
                    <h3 class="font-bold text-slate-900 text-lg"><i class="fa-solid fa-coins text-emerald-600 mr-2"></i> Enregistrer un Paiement</h3>
                    <p class="text-xs text-slate-500" x-text="activeDebt ? 'Dette #' + activeDebt.id + ' - ' + getClientName(activeDebt.customer_id) : ''"></p>
                </div>
                <button @click="closeModal('addPayment')" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>

            <!-- Context Info Card -->
            <template x-if="activeDebt">
                <div class="bg-slate-50 p-3 rounded-lg border border-slate-200 text-xs space-y-1">
                    <div class="flex justify-between">
                        <span class="text-slate-500">Montant Initial:</span>
                        <span class="font-bold" x-text="formatMoney(activeDebt.amount)"></span>
                    </div>
                    <div class="flex justify-between text-emerald-600">
                        <span>Déjà Payé:</span>
                        <span class="font-bold" x-text="formatMoney(getDebtPaidAmount(activeDebt.id))"></span>
                    </div>
                    <div class="flex justify-between text-rose-600 border-t pt-1 font-bold">
                        <span>Reste Dû Maximum:</span>
                        <span x-text="formatMoney(getDebtRemaining(activeDebt.id))"></span>
                    </div>
                </div>
            </template>

            <form @submit.prevent="submitAddPayment()" class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Montant à verser (FCFA) *</label>
                    <input type="number" step="0.01" min="1" :max="activeDebt ? getDebtRemaining(activeDebt.id) : 0" x-model="forms.payment.amount" required class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none font-bold text-slate-900" placeholder="Ex: 25000">
                    <p class="text-xs text-slate-400 mt-1">Ne peut dépasser le solde restant dû.</p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Date Paiement *</label>
                        <input type="datetime-local" x-model="forms.payment.payment_date" required class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Mode de Règlement *</label>
                        <select x-model="forms.payment.payment_method" required class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                            <option value="Espèces">Espèces</option>
                            <option value="MTN Mobile Money">MTN Mobile Money</option>
                            <option value="Orange Money">Orange Money</option>
                            <option value="Virement Bancaire">Virement Bancaire</option>
                            <option value="Carte Bancaire">Carte Bancaire</option>
                            <option value="Chèque">Chèque</option>
                        </select>
                    </div>
                </div>
                <div x-show="['MTN Mobile Money', 'Orange Money'].includes(forms.payment.payment_method)" x-cloak>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Référence de transaction *</label>
                    <input type="text" maxlength="100" x-model="forms.payment.transaction_reference" :required="['MTN Mobile Money', 'Orange Money'].includes(forms.payment.payment_method)" class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none" placeholder="ID de transaction du reçu">
                    <p class="mt-1 text-xs text-slate-500">Enregistrez le paiement après confirmation du transfert MTN ou Orange sur le téléphone.</p>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Commentaire</label>
                    <input type="text" x-model="forms.payment.notes" class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none" placeholder="Note complémentaire">
                </div>

                <div class="flex justify-end space-x-2 pt-3 border-t">
                    <button type="button" @click="closeModal('addPayment')" class="px-4 py-2 border rounded-lg text-sm font-medium text-slate-600 hover:bg-slate-50">Annuler</button>
                    <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-medium">Valider le Paiement</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal 4: Client Ledger & Debt History Drawer -->
    <div x-show="modals.clientLedger" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div @click.away="closeModal('clientLedger')" class="bg-white rounded-xl shadow-xl max-w-3xl w-full p-6 space-y-5 max-h-[90vh] flex flex-col">
            
            <template x-if="activeClient">
                <div class="flex justify-between items-start border-b pb-4">
                    <div>
                        <div class="flex items-center space-x-2">
                            <h3 class="font-bold text-slate-900 text-xl" x-text="activeClient.name"></h3>
                            <span class="text-xs px-2 py-0.5 bg-slate-100 text-slate-600 rounded font-mono" x-text="'ID #' + activeClient.id"></span>
                        </div>
                        <p class="text-sm text-slate-500 mt-0.5"><i class="fa-solid fa-phone text-slate-400 mr-1"></i> <span x-text="activeClient.phone"></span></p>
                    </div>
                    <div class="flex items-center gap-3"><button @click="exportClientLedger(activeClient.id)" title="Exporter l’historique du client en PDF" class="text-slate-500 hover:text-brand-700"><i class="fa-solid fa-file-pdf"></i></button><button @click="closeModal('clientLedger')" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark text-xl"></i></button></div>
                </div>
            </template>

            <!-- Ledger Summary Header -->
            <template x-if="activeClient">
                <div class="grid grid-cols-3 gap-3 bg-slate-50 p-3 rounded-lg text-center text-xs">
                    <div>
                        <span class="text-slate-400 block uppercase">Total Emprunté</span>
                        <span class="text-base font-bold text-slate-800" x-text="formatMoney(getClientTotalDebt(activeClient.id))"></span>
                    </div>
                    <div>
                        <span class="text-emerald-600 block uppercase">Total Remboursé</span>
                        <span class="text-base font-bold text-emerald-700" x-text="formatMoney(getClientTotalPaid(activeClient.id))"></span>
                    </div>
                    <div>
                        <span class="text-rose-600 block uppercase">Reste Global Dû</span>
                        <span class="text-base font-bold text-rose-700" x-text="formatMoney(getClientRemainingDebt(activeClient.id))"></span>
                    </div>
                </div>
            </template>

            <div class="overflow-x-auto rounded border border-slate-200">
                <h4 class="bg-slate-100 p-2 text-xs font-bold uppercase text-slate-600">Opérations chronologiques</h4>
                <table class="w-full text-left text-xs"><thead><tr class="text-slate-500"><th class="p-2">Date</th><th class="p-2">Opération</th><th class="p-2 text-right">Montant</th><th class="p-2 text-right">Reste</th></tr></thead>
                    <tbody class="divide-y divide-slate-100"><template x-for="(operation, index) in getClientOperations(activeClientId)" :key="index"><tr>
                        <td class="p-2" x-text="formatDateTime(operation.date)"></td><td class="p-2" x-text="operation.label"></td>
                        <td class="p-2 text-right" :class="operation.amount > 0 ? 'text-rose-700' : 'text-emerald-700'" x-text="(operation.amount > 0 ? '+' : '') + formatMoney(operation.amount)"></td>
                        <td class="p-2 text-right font-semibold" x-text="formatMoney(operation.remaining)"></td>
                    </tr></template><template x-if="getClientOperations(activeClientId).length === 0"><tr><td colspan="4" class="p-3 text-center text-slate-400">Aucune opération.</td></tr></template></tbody>
                </table>
            </div>

            <!-- Detailed Debts Ledger list -->
            <div class="flex-1 overflow-y-auto space-y-4 pr-1 custom-scrollbar">
                <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Historique Détail des Dettes & Règlements</h4>
                
                <template x-for="debt in getClientDebts(activeClientId)" :key="debt.id">
                    <div class="border border-slate-200 rounded-lg p-4 bg-white space-y-3 shadow-sm">
                        <div class="flex justify-between items-start">
                            <div>
                                <span class="font-mono text-xs text-slate-400" x-text="'Dette #' + debt.id + ' - Constatée le ' + formatDate(debt.debt_date)"></span>
                                <h5 class="font-bold text-slate-900" x-text="debt.description"></h5>
                            </div>
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold" :class="getStatusBadgeClass(debt)" x-text="getStatusLabel(debt)"></span>
                        </div>

                        <!-- Mathematical Progression bar -->
                        <div>
                            <div class="flex justify-between text-xs mb-1 font-medium">
                                <span x-text="'Payé: ' + formatMoney(getDebtPaidAmount(debt.id))"></span>
                                <span x-text="'Objectif: ' + formatMoney(debt.amount)"></span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                                <div class="bg-emerald-500 h-2 rounded-full transition-all duration-500" :style="'width: ' + Math.min(100, Math.round((getDebtPaidAmount(debt.id)/debt.amount)*100)) + '%'"></div>
                            </div>
                        </div>

                        <!-- List of payments for this debt -->
                        <div class="bg-slate-50 p-3 rounded-md text-xs space-y-2">
                            <span class="font-bold text-slate-600 block"><i class="fa-solid fa-list-check mr-1"></i> Règlements enregistrés pour cette dette :</span>
                            
                            <template x-for="p in getPaymentsForDebt(debt.id)" :key="p.id">
                                <div class="flex justify-between items-center border-b border-slate-200/60 pb-1 text-slate-700">
                                    <div>
                                        <span class="font-semibold text-emerald-700" x-text="formatMoney(p.amount)"></span>
                                        <span class="text-slate-400 ml-2" x-text="'(' + p.payment_method + ')'"></span>
                                        <span x-show="p.transaction_reference" class="ml-2 font-mono text-slate-500" x-text="'Réf. ' + p.transaction_reference"></span>
                                        <p x-show="p.notes" class="text-[11px] text-slate-400 italic" x-text="p.notes"></p>
                                    </div>
                                    <span class="text-slate-400 font-mono text-[11px]" x-text="formatDateTime(p.payment_date)"></span>
                                </div>
                            </template>

                            <template x-if="getPaymentsForDebt(debt.id).length === 0">
                                <p class="text-slate-400 italic text-center py-1">Aucun acompte versé pour l'instant.</p>
                            </template>
                        </div>
                    </div>
                </template>

                <template x-if="getClientDebts(activeClientId).length === 0">
                    <p class="text-center text-slate-400 py-6 italic">Ce client n'a aucune dette enregistrée.</p>
                </template>
            </div>

            <div class="pt-3 border-t flex justify-end">
                <button @click="closeModal('clientLedger')" class="px-4 py-2 bg-slate-800 text-white rounded-lg text-sm font-medium">Fermer</button>
            </div>
        </div>
    </div>


    <!-- ==================================================================== -->
    <!-- ALPINE JS LOGIC / APP CORE -->
    <!-- ==================================================================== -->
    <script>
        function debtApp() {
            return {
                currentTab: 'dashboard',
                statusFilter: 'ALL',
                activeSectorFilter: 'ALL',
                debtSearchQuery: '',
                clientSearchQuery: '',
                showArchivedClients: false,
                historySearch: '',
                historySector: 'ALL',
                historyStatus: 'ALL',
                historyStart: '',
                historyEnd: '',
                historyAmount: '',
                reportClientId: null,
                reportInvoiceId: null,
                reportMode: 'history',
                editingDebtId: null,
                csrfToken: '',
                loadError: '',
                adminMessage: '',
                adminPassword: '',
                isAdmin: false,
                resetSector: 'Snack',
                agentMessages: [],
                agentInput: '',
                agentBusy: false,
                agentError: '',
                agentStatus: '',
                
                // Modals state
                modals: {
                    addClient: false,
                    addDebt: false,
                    addPayment: false,
                    clientLedger: false
                },

                activeDebtId: null,
                activeClientId: null,

                // Forms binding state
                forms: {
                    client: { name: '', phone: '', notes: '' },
                    debt: { customer_id: '', sector: 'Snack', description: '', quantity: 1, amount: '', debt_date: new Date().toISOString().substring(0, 10), due_date: '', notes: '' },
                    payment: { amount: '', payment_date: new Date().toISOString().substring(0, 16), payment_method: 'Espèces', transaction_reference: '', notes: '' }
                },

                clients: [],
                debts: [],
                payments: [],
                resetHistory: [],

                async initData() {
                    try {
                        await this.refreshData();
                    } catch (error) {
                        this.loadError = error.message;
                    }
                },

                async refreshData() {
                    const response = await fetch('api.php?action=init', { credentials: 'same-origin' });
                    const data = await this.parseApiResponse(response);
                    this.csrfToken = data.csrfToken;
                    this.isAdmin = data.isAdmin;
                    this.clients = data.customers.map(client => ({ ...client, id: Number(client.id), phone: client.phone || '' }));
                    this.debts = data.debts.map(debt => ({ ...debt, id: Number(debt.id), customer_id: Number(debt.customer_id), amount: Number(debt.amount), total_paid: Number(debt.total_paid), quantity: Number(debt.quantity) }));
                    this.payments = data.payments.map(payment => ({ ...payment, id: Number(payment.id), debt_id: Number(payment.debt_id), amount: Number(payment.amount) }));
                    this.resetHistory = data.resets;
                    this.loadError = '';
                },

                async parseApiResponse(response) {
                    const body = await response.text();
                    let data;
                    try {
                        data = JSON.parse(body);
                    } catch (error) {
                        throw new Error('Le serveur a renvoyé une page HTML ou une réponse invalide. Vérifiez le journal PHP.');
                    }
                    if (!response.ok) throw new Error(data.error || 'La requête a échoué (HTTP ' + response.status + ').');
                    return data;
                },

                async retryData() {
                    try {
                        await this.refreshData();
                    } catch (error) {
                        this.loadError = error.message;
                    }
                },

                async postAction(action, payload) {
                    const response = await fetch('api.php?action=' + encodeURIComponent(action), {
                        method: 'POST', credentials: 'same-origin',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': this.csrfToken },
                        body: JSON.stringify(payload)
                    });
                    const data = await this.parseApiResponse(response);
                    if (data.csrfToken) this.csrfToken = data.csrfToken;
                    return data;
                },

                // Getters & Calculations
                getDebtPaidAmount(debtId) {
                    return this.payments
                        .filter(p => p.debt_id === debtId)
                        .reduce((sum, p) => sum + Number(p.amount), 0);
                },

                getDebtRemaining(debtId) {
                    const debt = this.debts.find(d => d.id === debtId);
                    if (!debt || debt.voided_at) return 0;
                    return Math.max(0, debt.amount - this.getDebtPaidAmount(debtId));
                },

                getDebtStatusKey(debt) {
                    if (debt.voided_at) return 'VOID';
                    const paid = this.getDebtPaidAmount(debt.id);
                    const remaining = debt.amount - paid;
                    const todayStr = new Date().toISOString().substring(0, 10);

                    if (remaining <= 0) return 'PAID';
                    if (debt.due_date < todayStr) return 'OVERDUE';
                    if (paid > 0) return 'PARTIAL';
                    return 'UNPAID';
                },

                isOverdue(debt) {
                    return this.getDebtStatusKey(debt) === 'OVERDUE';
                },

                getStatusBadgeClass(debt) {
                    const status = this.getDebtStatusKey(debt);
                    switch(status) {
                        case 'VOID': return 'bg-slate-100 text-slate-600 border border-slate-300';
                        case 'PAID': return 'bg-emerald-100 text-emerald-800 border border-emerald-300';
                        case 'PARTIAL': return 'bg-amber-100 text-amber-800 border border-amber-300';
                        case 'OVERDUE': return 'bg-red-100 text-red-800 border border-red-300';
                        default: return 'bg-rose-100 text-rose-800 border border-rose-300';
                    }
                },

                getStatusIcon(debt) {
                    const status = this.getDebtStatusKey(debt);
                    switch(status) {
                        case 'VOID': return '⚪';
                        case 'PAID': return '🟢';
                        case 'PARTIAL': return '🟡';
                        case 'OVERDUE': return '⚠️';
                        default: return '🔴';
                    }
                },

                getStatusLabel(debt) {
                    const status = this.getDebtStatusKey(debt);
                    switch(status) {
                        case 'VOID': return 'Annulée';
                        case 'PAID': return 'Payé';
                        case 'PARTIAL': return 'Partiellement Payé';
                        case 'OVERDUE': return 'En Retard';
                        default: return 'Non Payé';
                    }
                },

                // Dashboard Statistics computed dynamically
                get stats() {
                    let totalAmount = 0;
                    let totalPaid = 0;
                    let totalRemaining = 0;
                    
                    let unpaidCount = 0;
                    let unpaidAmount = 0;
                    let partialCount = 0;
                    let partialRemainingAmount = 0;
                    let overdueCount = 0;
                    let overdueAmount = 0;
                    let paidCount = 0;

                    const debtorIds = new Set();

                    this.debts.filter(debt => !debt.archived_at && !debt.voided_at).forEach(debt => {
                        const paid = this.getDebtPaidAmount(debt.id);
                        const remaining = debt.amount - paid;
                        const status = this.getDebtStatusKey(debt);

                        totalAmount += Number(debt.amount);
                        totalPaid += paid;
                        totalRemaining += remaining;

                        if (remaining > 0) {
                            debtorIds.add(debt.customer_id);
                        }

                        if (status === 'UNPAID') {
                            unpaidCount++;
                            unpaidAmount += remaining;
                        } else if (status === 'PARTIAL') {
                            partialCount++;
                            partialRemainingAmount += remaining;
                        } else if (status === 'OVERDUE') {
                            overdueCount++;
                            overdueAmount += remaining;
                        } else if (status === 'PAID') {
                            paidCount++;
                        }
                    });

                    return {
                        debtCount: this.debts.filter(debt => !debt.archived_at && !debt.voided_at).length,
                        totalAmount,
                        totalPaid,
                        totalRemaining,
                        debtorCount: debtorIds.size,
                        unpaidCount,
                        unpaidAmount,
                        partialCount,
                        partialRemainingAmount,
                        overdueCount,
                        overdueAmount,
                        paidCount
                    };
                },

                // Filters
                get filteredDebts() {
                    return this.debts.filter(debt => !debt.archived_at && !debt.voided_at).filter(debt => {
                        const clientName = this.getClientName(debt.customer_id).toLowerCase();
                        const clientPhone = this.getClientPhone(debt.customer_id).toLowerCase();
                        const desc = debt.description.toLowerCase();
                        const query = this.debtSearchQuery.toLowerCase();

                        const matchesSearch = clientName.includes(query) || clientPhone.includes(query) || desc.includes(query);
                        const statusKey = this.getDebtStatusKey(debt);
                        const matchesStatus = this.statusFilter === 'ALL' || statusKey === this.statusFilter;

                        const matchesSector = this.activeSectorFilter === 'ALL' || debt.sector === this.activeSectorFilter;
                        return matchesSearch && matchesStatus && matchesSector;
                    });
                },

                get historyDebts() {
                    const query = this.historySearch.trim().toLocaleLowerCase('fr');
                    return this.debts.filter(debt => {
                        const status = this.getDebtStatusKey(debt);
                        const amountMatches = this.historyAmount === '' || Number(debt.amount) === Number(this.historyAmount);
                        return (!query || this.getClientName(debt.customer_id).toLocaleLowerCase('fr').includes(query))
                            && (this.historySector === 'ALL' || debt.sector === this.historySector)
                            && (this.historyStatus === 'ALL' || status === this.historyStatus)
                            && (!this.historyStart || debt.debt_date >= this.historyStart)
                            && (!this.historyEnd || debt.debt_date <= this.historyEnd)
                            && amountMatches;
                    });
                },

                get invoiceDebt() {
                    return this.reportInvoiceId ? this.getDebtById(Number(this.reportInvoiceId)) : null;
                },

                get reportDebts() {
                    if (this.reportInvoiceId) return this.invoiceDebt ? [this.invoiceDebt] : [];
                    if (this.reportClientId) return this.getClientDebts(this.reportClientId);
                    return this.reportMode === 'active' ? this.filteredDebts : this.historyDebts;
                },

                get reportTotals() {
                    const financialDebts = this.reportDebts.filter(item => !item.voided_at);
                    const debt = financialDebts.reduce((sum, item) => sum + Number(item.amount), 0);
                    const paid = financialDebts.reduce((sum, item) => sum + this.getDebtPaidAmount(item.id), 0);
                    return { debt, paid, remaining: debt - paid, customers: new Set(financialDebts.map(item => item.customer_id)).size };
                },

                get activeClients() {
                    return this.clients.filter(client => !client.archived_at);
                },

                get archivedClientCount() {
                    return this.clients.filter(client => Boolean(client.archived_at)).length;
                },

                get filteredClients() {
                    return this.clients.filter(client => Boolean(client.archived_at) === this.showArchivedClients).filter(c => {
                        const q = this.clientSearchQuery.toLowerCase();
                        return c.name.toLowerCase().includes(q) || c.phone.toLowerCase().includes(q);
                    });
                },

                get orderedPayments() {
                    return [...this.payments].sort((left, right) => left.payment_date.localeCompare(right.payment_date));
                },

                getOverdueDebts() {
                    return this.debts.filter(d => !d.archived_at && !d.voided_at && this.getDebtStatusKey(d) === 'OVERDUE');
                },

                // Client-specific helpers
                getClientName(id) {
                    const c = this.clients.find(x => x.id === Number(id));
                    return c ? c.name : 'Client inconnu';
                },

                getClientPhone(id) {
                    const c = this.clients.find(x => x.id === Number(id));
                    return c ? c.phone : '';
                },

                get activeClient() {
                    return this.clients.find(c => c.id === this.activeClientId);
                },

                getClientDebts(clientId) {
                    return this.debts.filter(d => d.customer_id === Number(clientId));
                },

                hasClientActiveBalance(clientId) {
                    return this.getClientDebts(clientId).some(debt => !debt.archived_at && !debt.voided_at && this.getDebtRemaining(debt.id) > 0);
                },

                getClientOperations(clientId) {
                    const operations = [];
                    this.getClientDebts(clientId).forEach(debt => {
                        if (debt.voided_at) {
                            operations.push({ date: debt.voided_at.replace(' ', 'T'), amount: 0, label: 'Dette annulée · ' + debt.void_reason });
                            return;
                        }
                        operations.push({ date: debt.debt_date + 'T00:00:00', amount: Number(debt.amount), label: 'Dette · ' + debt.sector + ' · ' + debt.description });
                        this.getPaymentsForDebt(debt.id).forEach(payment => operations.push({
                            date: payment.payment_date.replace(' ', 'T'), amount: -Number(payment.amount), label: 'Paiement · ' + payment.payment_method
                        }));
                    });
                    operations.sort((left, right) => left.date.localeCompare(right.date) || right.amount - left.amount);
                    let balance = 0;
                    return operations.map(operation => {
                        balance += operation.amount;
                        return { ...operation, remaining: Math.max(0, balance) };
                    });
                },

                getClientTotalDebt(clientId) {
                    return this.getClientDebts(clientId).filter(debt => !debt.voided_at).reduce((sum, d) => sum + Number(d.amount), 0);
                },

                getClientTotalPaid(clientId) {
                    return this.getClientDebts(clientId).filter(debt => !debt.voided_at).reduce((sum, d) => sum + this.getDebtPaidAmount(d.id), 0);
                },

                getClientRemainingDebt(clientId) {
                    return this.getClientTotalDebt(clientId) - this.getClientTotalPaid(clientId);
                },

                getPaymentsForDebt(debtId) {
                    return this.payments.filter(p => p.debt_id === debtId);
                },

                getDebtById(debtId) {
                    return this.debts.find(d => d.id === debtId);
                },

                get activeDebt() {
                    return this.getDebtById(this.activeDebtId);
                },

                getDaysOverdue(dueDateStr) {
                    const due = new Date(dueDateStr);
                    const now = new Date();
                    const diffTime = now - due;
                    return Math.max(1, Math.floor(diffTime / (1000 * 60 * 60 * 24)));
                },

                // Modal Actions
                emptyDebtForm() {
                    return { customer_id: '', sector: 'Snack', description: '', quantity: 1, amount: '', debt_date: new Date().toISOString().substring(0, 10), due_date: '', notes: '' };
                },

                openNewDebtModal() {
                    this.editingDebtId = null;
                    this.forms.debt = this.emptyDebtForm();
                    this.openModal('addDebt');
                },

                editDebt(debt) {
                    if (debt.archived_at || debt.voided_at) return;
                    this.editingDebtId = debt.id;
                    this.forms.debt = {
                        customer_id: String(debt.customer_id), sector: debt.sector, description: debt.description,
                        quantity: debt.quantity, amount: debt.amount, debt_date: debt.debt_date,
                        due_date: debt.due_date, notes: debt.notes || '',
                    };
                    this.currentTab = 'debts';
                    this.openModal('addDebt');
                },

                openModal(name) {
                    this.modals[name] = true;
                },

                closeModal(name) {
                    this.modals[name] = false;
                    if (name === 'addDebt') this.editingDebtId = null;
                },

                setDebtFilter(status) {
                    this.currentTab = 'debts';
                    this.statusFilter = status;
                },

                selectSector(sector) {
                    this.currentTab = 'debts';
                    this.activeSectorFilter = sector;
                    this.statusFilter = 'ALL';
                },

                openPaymentModal(debt) {
                    if (debt.archived_at || debt.voided_at) return;
                    this.activeDebtId = debt.id;
                    const remaining = this.getDebtRemaining(debt.id);
                    this.forms.payment.amount = remaining;
                    this.forms.payment.payment_date = new Date().toISOString().substring(0, 16);
                    this.forms.payment.transaction_reference = '';
                    this.forms.payment.notes = '';
                    this.openModal('addPayment');
                },

                openClientLedger(clientId) {
                    this.activeClientId = clientId;
                    this.openModal('clientLedger');
                },

                // Form Submissions (Simulated server-side processing)
                async submitAddClient() {
                    try {
                        await this.postAction('create_customer', this.forms.client);
                        await this.refreshData();
                        this.forms.client = { name: '', phone: '', notes: '' };
                        this.closeModal('addClient');
                    } catch (error) { alert(error.message); }
                },

                async archiveClient(client) {
                    if (client.archived_at || this.hasClientActiveBalance(client.id)) return;
                    const reason = prompt('Indiquez le motif d’archivage de ce client :');
                    if (reason === null || !reason.trim()) return;
                    if (!confirm('Retirer ce client de la liste active ? Ses dettes et paiements seront conservés dans l’historique.')) return;
                    try {
                        await this.postAction('archive_customer', { id: client.id, reason: reason.trim() });
                        await this.refreshData();
                        this.showArchivedClients = true;
                    } catch (error) { alert(error.message); }
                },

                async submitSaveDebt() {
                    try {
                        if (this.editingDebtId) {
                            await this.postAction('update_debt', { ...this.forms.debt, id: this.editingDebtId });
                        } else {
                            await this.postAction('create_debt', this.forms.debt);
                        }
                        await this.refreshData();
                        this.forms.debt = this.emptyDebtForm();
                        this.editingDebtId = null;
                        this.closeModal('addDebt');
                    } catch (error) { alert(error.message); }
                },

                async submitAddPayment() {
                    const debtId = this.activeDebtId;
                    const debt = this.getDebtById(debtId);
                    if (!debt) return;

                    const payAmount = Number(this.forms.payment.amount);
                    const remaining = this.getDebtRemaining(debtId);

                    // Client-side security check (replicated on PHP backend)
                    if (payAmount > remaining) {
                        alert(`Erreur de sécurité : Le montant saisi (${payAmount}) dépasse le solde restant dû (${remaining}).`);
                        return;
                    }
                    try {
                        await this.postAction('create_payment', { ...this.forms.payment, debt_id: debtId });
                        await this.refreshData();
                        this.closeModal('addPayment');
                    } catch (error) { alert(error.message); }
                },

                async voidDebt(debt) {
                    if (debt.archived_at || debt.voided_at || this.getDebtPaidAmount(debt.id) > 0) return;
                    const reason = prompt('Indiquez le motif de suppression de cette dette :');
                    if (reason === null || !reason.trim()) return;
                    if (!confirm('Cette dette sera retirée des totaux actifs, mais conservée dans l’historique. Continuer ?')) return;
                    try {
                        await this.postAction('void_debt', { id: debt.id, reason: reason.trim() });
                        await this.refreshData();
                    } catch (error) { alert(error.message); }
                },

                async sendAgentMessage() {
                    const content = this.agentInput.trim();
                    if (!content || this.agentBusy) return;
                    this.agentMessages.push({ role: 'user', content });
                    this.agentInput = '';
                    this.agentBusy = true;
                    this.agentError = '';
                    this.agentStatus = '';
                    try {
                        const result = await this.postAction('ai_chat', { messages: this.agentMessages.slice(-10).map(({ role, content: text }) => ({ role, content: text })) });
                        this.agentMessages.push({ role: 'assistant', content: result.reply, proposal: result.proposal });
                    } catch (error) {
                        this.agentError = error.message;
                    } finally {
                        this.agentBusy = false;
                    }
                },

                prepareAgentAction(proposal) {
                    this.agentError = '';
                    if (proposal.type === 'create_debt') {
                        this.editingDebtId = null;
                        this.forms.debt = {
                            customer_id: String(proposal.customer_id), sector: proposal.sector, description: proposal.description,
                            quantity: proposal.quantity, amount: proposal.amount, debt_date: proposal.debt_date,
                            due_date: proposal.due_date, notes: 'Proposition préparée par l’agent IA. À vérifier.',
                        };
                        this.currentTab = 'debts';
                        this.openModal('addDebt');
                        return;
                    }
                    if (proposal.type === 'record_payment') {
                        const debt = this.getDebtById(Number(proposal.debt_id));
                        if (!debt || proposal.amount > this.getDebtRemaining(debt.id)) {
                            this.agentError = 'Le solde a changé. Vérifiez la dette avant de préparer le paiement.';
                            return;
                        }
                        this.currentTab = 'debts';
                        this.openPaymentModal(debt);
                        this.forms.payment.amount = proposal.amount;
                        this.forms.payment.payment_method = proposal.payment_method;
                        this.forms.payment.transaction_reference = proposal.transaction_reference || '';
                        this.forms.payment.notes = 'Proposition préparée par l’agent IA. À vérifier.';
                    }
                },

                async copyAgentReminder(message) {
                    try {
                        await navigator.clipboard.writeText(message);
                        this.agentStatus = 'Message copié. Il n’a pas été envoyé.';
                    } catch (error) {
                        this.agentError = 'Impossible de copier le message dans le presse-papiers.';
                    }
                },

                async adminLogin() {
                    try {
                        await this.postAction('admin_login', { password: this.adminPassword });
                        await this.refreshData();
                        this.adminPassword = '';
                        this.adminMessage = '';
                    } catch (error) { this.adminMessage = error.message; }
                },

                async adminLogout() {
                    try {
                        await this.postAction('admin_logout', {});
                        await this.refreshData();
                    } catch (error) { this.adminMessage = error.message; }
                },

                async resetDebts() {
                    if (!this.isAdmin) return;
                    const label = this.resetSector === 'Tous' ? 'Snack et Cave' : this.resetSector;
                    if (!confirm(`Archiver toutes les dettes actives du secteur ${label} ? Cette opération ne supprime aucun historique.`)) return;
                    try {
                        const result = await this.postAction('reset', { sector: this.resetSector });
                        await this.refreshData();
                        alert(`${result.archived_count} dette(s) archivée(s). Total avant archivage : ${this.formatMoney(result.total_debt)}.`);
                    } catch (error) { this.adminMessage = error.message; }
                },

                exportHistory() {
                    this.reportClientId = null;
                    this.reportInvoiceId = null;
                    this.reportMode = 'history';
                    this.$nextTick(() => window.print());
                },

                exportActiveDebts() {
                    this.reportClientId = null;
                    this.reportInvoiceId = null;
                    this.reportMode = 'active';
                    this.$nextTick(() => window.print());
                },

                exportInvoice(debt) {
                    this.reportClientId = null;
                    this.reportInvoiceId = debt.id;
                    this.reportMode = 'history';
                    this.$nextTick(() => {
                        window.print();
                        this.reportInvoiceId = null;
                    });
                },

                exportClientLedger(clientId) {
                    this.reportInvoiceId = null;
                    this.reportClientId = clientId;
                    this.$nextTick(() => {
                        window.print();
                        this.reportClientId = null;
                    });
                },

                // Formatting utilities
                formatMoney(val) {
                    return new Intl.NumberFormat('fr-FR', { maximumFractionDigits: 0 }).format(val || 0) + ' FCFA';
                },

                formatDate(dateStr) {
                    if (!dateStr) return '';
                    const d = new Date(dateStr);
                    return d.toLocaleDateString('fr-FR');
                },

                formatDateTime(dateTimeStr) {
                    if (!dateTimeStr) return '';
                    const d = new Date(dateTimeStr);
                    return d.toLocaleDateString('fr-FR') + ' à ' + d.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });
                }
            };
        }
    </script>
</body>
</html>