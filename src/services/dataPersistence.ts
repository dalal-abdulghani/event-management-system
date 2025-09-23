// Data Persistence Service
// This service handles saving and loading data from localStorage
// In a real application, this would connect to a backend API

interface StoredData {
      events: any[]
      venues: any[]
      users: any[]
      discountCodes: any[]
      ticketTemplates: any[]
      campaigns: any[]
      sessions: any[]
      speakers: any[]
      roles: any[]
      apiKeys: any[]
      webhooks: any[]
      customers: any[]
      lastUpdated: string
}

class DataPersistenceService {
      private storageKey = 'eventManagementData'

      // Load all data from localStorage
      loadAllData(): StoredData {
            const defaultData: StoredData = {
                  events: [],
                  venues: [],
                  users: [],
                  discountCodes: [],
                  ticketTemplates: [],
                  campaigns: [],
                  sessions: [],
                  speakers: [],
                  roles: [],
                  apiKeys: [],
                  webhooks: [],
                  customers: [],
                  lastUpdated: new Date().toISOString()
            }

            try {
                  const stored = localStorage.getItem(this.storageKey)
                  if (stored) {
                        const parsed = JSON.parse(stored)
                        return { ...defaultData, ...parsed }
                  }
            } catch (error) {
                  console.error('Error loading data from localStorage:', error)
            }

            return defaultData
      }

      // Save all data to localStorage
      saveAllData(data: StoredData): void {
            try {
                  data.lastUpdated = new Date().toISOString()
                  localStorage.setItem(this.storageKey, JSON.stringify(data))
                  console.log('Data saved successfully to localStorage')
            } catch (error) {
                  console.error('Error saving data to localStorage:', error)
            }
      }

      // Generic save method for any collection
      saveCollection<T>(collectionName: keyof StoredData, items: T[]): void {
            const data = this.loadAllData()
            data[collectionName] = items as any
            this.saveAllData(data)
      }

      // Generic load method for any collection
      loadCollection<T>(collectionName: keyof StoredData): T[] {
            const data = this.loadAllData()
            return (data[collectionName] || []) as T[]
      }

      // Add item to collection
      addItem<T>(collectionName: keyof StoredData, item: T): void {
            const data = this.loadAllData()
            const collection = data[collectionName] as T[]
            collection.push(item)
            this.saveAllData(data)
      }

      // Update item in collection
      updateItem<T>(collectionName: keyof StoredData, itemId: number, updatedItem: T): boolean {
            const data = this.loadAllData()
            const collection = data[collectionName] as any[]
            const index = collection.findIndex((item: any) => item.id === itemId)

            if (index !== -1) {
                  collection[index] = { ...collection[index], ...updatedItem }
                  this.saveAllData(data)
                  return true
            }
            return false
      }

      // Delete item from collection
      deleteItem(collectionName: keyof StoredData, itemId: number): boolean {
            const data = this.loadAllData()
            const collection = data[collectionName] as any[]
            const index = collection.findIndex((item: any) => item.id === itemId)

            if (index !== -1) {
                  collection.splice(index, 1)
                  this.saveAllData(data)
                  return true
            }
            return false
      }

      // Clear all data (for testing/reset purposes)
      clearAllData(): void {
            localStorage.removeItem(this.storageKey)
            console.log('All data cleared from localStorage')
      }

      // Get data statistics
      getDataStats(): { [key: string]: number } {
            const data = this.loadAllData()
            const stats: { [key: string]: number } = {}

            Object.keys(data).forEach(key => {
                  if (key !== 'lastUpdated') {
                        stats[key] = (data[key as keyof StoredData] as any[]).length
                  }
            })

            return stats
      }

      // Export data (for backup purposes)
      exportData(): string {
            const data = this.loadAllData()
            return JSON.stringify(data, null, 2)
      }

      // Import data (for restore purposes)
      importData(jsonData: string): boolean {
            try {
                  const data = JSON.parse(jsonData)
                  this.saveAllData(data)
                  return true
            } catch (error) {
                  console.error('Error importing data:', error)
                  return false
            }
      }
}

// Create singleton instance
export const dataPersistence = new DataPersistenceService()

// Helper functions for common operations
export const saveEvent = (event: any) => dataPersistence.addItem('events', event)
export const updateEvent = (eventId: number, event: any) => dataPersistence.updateItem('events', eventId, event)
export const deleteEvent = (eventId: number) => dataPersistence.deleteItem('events', eventId)
export const loadEvents = () => dataPersistence.loadCollection('events')

export const saveVenue = (venue: any) => dataPersistence.addItem('venues', venue)
export const updateVenue = (venueId: number, venue: any) => dataPersistence.updateItem('venues', venueId, venue)
export const deleteVenue = (venueId: number) => dataPersistence.deleteItem('venues', venueId)
export const loadVenues = () => dataPersistence.loadCollection('venues')

export const saveUser = (user: any) => dataPersistence.addItem('users', user)
export const updateUser = (userId: number, user: any) => dataPersistence.updateItem('users', userId, user)
export const deleteUser = (userId: number) => dataPersistence.deleteItem('users', userId)
export const loadUsers = () => dataPersistence.loadCollection('users')

export const saveDiscountCode = (discount: any) => dataPersistence.addItem('discountCodes', discount)
export const updateDiscountCode = (discountId: number, discount: any) => dataPersistence.updateItem('discountCodes', discountId, discount)
export const deleteDiscountCode = (discountId: number) => dataPersistence.deleteItem('discountCodes', discountId)
export const loadDiscountCodes = () => dataPersistence.loadCollection('discountCodes')

export default dataPersistence
