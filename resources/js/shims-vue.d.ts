declare module '*.vue' {
      import type { DefineComponent } from 'vue'
      const component: DefineComponent<{}, {}, any>
      export default component
}

// Laravel global types
declare global {
      interface Window {
            Laravel?: {
                  csrfToken: string
                  apiUrl: string
                  appUrl: string
            }
      }
}

