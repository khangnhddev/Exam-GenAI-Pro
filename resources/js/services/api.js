import axios from 'axios'

export const adminApi = {
    getDashboardStats() {
        return axios.get('/admin/dashboard/stats')
    },
    
    // Add more admin API endpoints here
    getExamStats() {
        return axios.get('/admin/exams/stats')
    },
    
    getUserStats() {
        return axios.get('/admin/users/stats') 
    }
}

// Other API services
export const examApi = {
    // Exam related endpoints
}

export const authApi = {
    // Auth related endpoints  
}