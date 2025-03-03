export default [
    {
        path: '/admin/settings/ai',
        name: 'ai-settings',
        redirect: '/admin/settings/ai/models',
        children: [
            {
                path: 'models',
                name: 'ai-models',
                component: () => import('@/views/admin/settings/ai/ModelConfiguration.vue'),
                meta: {
                    title: 'AI Model Configuration',
                    requiresAuth: true,
                    adminOnly: true
                }
            },
            {
                path: 'api-keys',
                name: 'ai-api-keys',
                component: () => import('@/views/admin/settings/ai/ApiKeys.vue'),
                meta: {
                    title: 'AI API Keys',
                    requiresAuth: true,
                    adminOnly: true
                }
            },
            {
                path: 'usage',
                name: 'ai-usage',
                component: () => import('@/views/admin/settings/ai/Usage.vue'),
                meta: {
                    title: 'AI Usage & Limits',
                    requiresAuth: true,
                    adminOnly: true
                }
            },
            {
                path: 'defaults',
                name: 'ai-defaults',
                component: () => import('@/views/admin/settings/ai/DefaultSettings.vue'),
                meta: {
                    title: 'AI Default Settings',
                    requiresAuth: true,
                    adminOnly: true
                }
            }
        ]
    }
] 