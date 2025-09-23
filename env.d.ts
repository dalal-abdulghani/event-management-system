/// <reference types="vite/client" />

declare module '@/*' {
      const content: any
      export default content
}

declare module '@/types' {
      export * from '../src/types'
}

declare module '@/services/api' {
      export * from '../src/services/api'
}

declare module '@/stores/auth' {
      export * from '../src/stores/auth'
}
