import axios from 'axios';

const examService = {
    // Get all exams
    getAllExams() {
        return axios.get('/exams');
    },

    // Get a single exam by ID
    getExam(id) {
        return axios.get(`/exams/${id}`);
    },

    // Get exam questions
    getExamQuestions(examId) {
        return axios.get(`/exams/${examId}/questions`);
    },

    // Start an exam session
    startExam(examId) {
        return axios.post(`/exams/${examId}/start`);
    },

    // Submit exam answers
    submitExam(examId, answers) {
        return axios.post(`/exams/${examId}/submit`, { answers });
    }
};

export default examService; 