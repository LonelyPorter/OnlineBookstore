package com.example.bookstore;

import androidx.annotation.Nullable;
import androidx.appcompat.app.AppCompatActivity;

import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.TextView;
import android.widget.Toast;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.lang.reflect.Method;
import java.util.HashMap;
import java.util.Map;

public class BookActivity extends AppCompatActivity {
    String ISBN; // book ISBN number
    String id;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_book);
        RequestQueue requestQueue = Volley.newRequestQueue(this);

        ISBN = getIntent().getStringExtra("ISBN");
        id = getIntent().getStringExtra("id");
        Log.d("Book", "ISBN: "+ISBN);

        StringRequest stringRequest = new StringRequest(Request.Method.POST, getString(R.string.url) + "book.php",
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        // debug information
                        Log.d("Book", "response: "+response);

                        // info variable
                        String title = "", type = "", price = "", category = "", author = "", publisher = "";
                        // retrieve the data
                        JSONArray book = null;
                        try {
                            book = new JSONArray(response);
                            JSONObject info = book.getJSONObject(0);

                            title = info.getString("title");
                            type = info.getString("type");
                            price = info.getString("price");
                            author = info.getString("author");
                            publisher = info.getString("pName");
                            category = info.getString("Category");
                        } catch (JSONException e) {
                            e.printStackTrace();
                        }

                        // edit Text
                        TextView titleText = findViewById(R.id.titleText);
                        TextView isbnText = findViewById(R.id.isbnText);
                        TextView categoryText = findViewById(R.id.CategoryText);
                        TextView priceText = findViewById(R.id.priceText);
                        TextView typeText = findViewById(R.id.typeText);
                        TextView authorText = findViewById(R.id.authorText);
                        TextView publisherText = findViewById(R.id.publisherText);

                        titleText.setText(title);
                        isbnText.append(ISBN);
                        categoryText.append(category);
                        priceText.append("$"+price);
                        typeText.append(type);
                        authorText.append(author);
                        publisherText.append(publisher);

                    }
                }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {

            }
        }) {
            @Nullable
            @Override
            protected Map<String, String> getParams() throws AuthFailureError {
                Map<String, String> map = new HashMap<>();
                map.put("book", ISBN);
                return map;
            }
        };

        requestQueue.add(stringRequest);
    }

    public void addCartOnClicked(View view) {
        RequestQueue requestQueue = Volley.newRequestQueue(this);

        StringRequest stringRequest = new StringRequest(Request.Method.POST, getString(R.string.url) + "add_cart.php",
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        Log.d("Add Cart", "response: "+response);

                        Toast.makeText(BookActivity.this, "add cart succeed!", Toast.LENGTH_SHORT).show();
                    }
                }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {

            }
        }) {
            @Nullable
            @Override
            protected Map<String, String> getParams() throws AuthFailureError {
                Map<String, String> map = new HashMap<>();
                map.put("ISBN", ISBN);
                map.put("id", id);
                return map;
            }
        };

        // send request
        requestQueue.add(stringRequest);
    }
}