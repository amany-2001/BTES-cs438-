public class Payment {
    private int paymentID;
    private String paymentMethod;
    private int userID;
    private float amount; 
    private String state;
    private Ticket ticketID;

    public boolean processPayment(String paymentMethod,float price,int userID,String accountNumber,String password){
        return false;
        
    }
    public String refundPayment(String state){
        return state;
        
    }
    public String getPaymentStatus(){
        return state;
        
    }
}
