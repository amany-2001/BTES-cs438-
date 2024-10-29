import java.sql.Date;
import java.util.ArrayList;
import java.util.List;

public class Event {
    private int eventID;
    private String eventName;
    private String category;
    private Date date;
    private String location;
    private List<Seat> seats;

    public Event(int eventID) {
        this.eventID = eventID;
        this.seats = new ArrayList<>();
        // إضافة مقاعد افتراضية للحدث
        for (int i = 1; i <= 100; i++) {
            seats.add(new Seat(i, true)); // جميع المقاعد متاحة افتراضيًا
        }
    }


    public void displayDetails() {
            // طباعة التفاصيل
        System.out.println("Event ID: " + eventID);
        System.out.println("Event Name: " + eventName);
        System.out.println("Category: " + category);
        System.out.println("Date: " + date);
        System.out.println("Location: " + location);
    }
        
    
    public List<Seat> get_avalible_seats(){
        List<Seat> availableSeats = new ArrayList<>();
        for (Seat seat : seats) {
            if (seat.is_seat_available()) {
                availableSeats.add(seat);
            }
        }
        return availableSeats;
    }
    public boolean researv_seat(int seatNumber){
        for (Seat seat : seats) {
            if (seat.getSeatNumber() == seatNumber) {
                if (seat.is_seat_available()) {
                    seat.setAvailable(false);
                    return true; // تم حجز المقعد بنجاح
                } else {
                    return false; // المقعد غير متاح
                }
            }
        }
        return false; 
    }

    public boolean isSeatAvailable(int seatNumber) {
        for (Seat seat : seats) {
            if (seat.getSeatNumber() == seatNumber) {
                return seat.is_seat_available();
            }
        }
        return false; // المقعد غير موجود
    }
}
