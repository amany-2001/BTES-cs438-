
public class Seat {
    private int seatNumber;
    private boolean isAvailable;
    public Seat(int seatNumber, boolean isAvailable) {
        this.seatNumber = seatNumber;
        this.isAvailable = isAvailable;
    }
    public int getSeatNumber() {
        return seatNumber;
    }
    void setAvailable(boolean isAvailable) {
        this.isAvailable=isAvailable;
    }
    public boolean choose_seat(){
        if (isAvailable) {
            setAvailable(false);
            return true; // تم اختيار المقعد بنجاح
        } else {
            return false; // المقعد غير متاح
        }
    }
    public void reserv_seats(boolean available){
        isAvailable = available;
        
    }
    public boolean is_seat_available(){
        return isAvailable;
        
    }
}
