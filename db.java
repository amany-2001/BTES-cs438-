import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.ResultSet;
import java.sql.Statement;
import java.util.ArrayList;
import java.util.List;

public class db {
    public static void main(String[] args) {
        String url = "jdbc:mysql://localhost:3306/btes";
        String usr = "root";
        String password = " ";

        List<User> users = new ArrayList<>();
        List<Ticket> tickets = new ArrayList<>();
        List<Event> events = new ArrayList<>();
        List<Notification> notifications = new ArrayList<>();
        List<Payment> payments = new ArrayList<>();
        List<Discount> discounts = new ArrayList<>();
        List<Vip> vips = new ArrayList<>();
        List<Seat> seats = new ArrayList<>();
        

        try {
            Connection connection = DriverManager.getConnection(url, usr, password);
            Statement statement = connection.createStatement();

            // استرجاع بيانات المستخدم
            ResultSet resultusers = statement.executeQuery("SELECT * FROM user");
            while (resultusers.next()) {
                User user = new User(resultusers.getInt("userID"), resultusers.getString("userName"), resultusers.getInt("userAge"),resultusers.getString("phone"),resultusers.getString("email"),resultusers.getString("accountNumber"),resultusers.getString("password"),resultusers.getInt("point"));
                users.add(user);
            }

            // استرجاع بيانات الحدت
            
            ResultSet resultevent= statement.executeQuery("SELECT * FROM event");
            while (resultevent.next()) {
                Event event = new Event(resultevent.getInt("eventID"), resultevent.getString("eventName"), resultevent.getstring("category"),resultevent.getDate("date"),resultevent.getString("loacation");
                event.add(event);
            }
        
            
            resultuser.close();
            resultevent.close();
            resultSeats.close();
            statement.close();
            connection.close();
        } catch (Exception e) {
            e.printStackTrace();
        }
    }
}



