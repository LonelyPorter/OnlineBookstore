package com.example.bookstore;

import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.view.Gravity;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.ImageButton;
import android.widget.TableLayout;
import android.widget.TableRow;
import android.widget.TextView;

import androidx.annotation.Nullable;
import androidx.appcompat.app.AppCompatActivity;

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

import java.util.HashMap;
import java.util.Map;

public class StoreActivity extends AppCompatActivity {
    Button search;
    Button order;
    ImageButton profile;
    ImageButton cart;
    String id;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_store);

        order = findViewById(R.id.storeOrder_btn);
        search = findViewById(R.id.storeSearch_btn);
        profile = findViewById(R.id.profile_btn);
        cart = findViewById(R.id.cart_btn);
        id = getIntent().getStringExtra("id");

        RequestQueue requestQueue = Volley.newRequestQueue(this);

        // order button
        order.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent orderPage = new Intent(StoreActivity.this, OrderActivity.class);

                orderPage.putExtra("id", id);

                startActivity(orderPage);
            }
        });

        // search button
        search.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent searchPage = new Intent(StoreActivity.this, SearchActivity.class);
                searchPage.putExtra("id", id);

                startActivity(searchPage);
            }
        });

        // profile button
        profile.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent profilePage = new Intent(StoreActivity.this, ProfileActivity.class);
                // put the id of the user
                profilePage.putExtra("id", id);

                // start the activity
                startActivity(profilePage);
            }
        });

        // cart button
        cart.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent cartPage = new Intent(StoreActivity.this, CartActivity.class);
                // pass in the id to the next page
                cartPage.putExtra("id", id);

                // start the page
                startActivity(cartPage);
            }
        });

        // store display
        StringRequest stringRequest = new StringRequest(Request.Method.POST,
                getString(R.string.url) + "store.php",
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        Context context = StoreActivity.this;
                        TableLayout storeTable = (TableLayout) findViewById(R.id.storeTable);

                        // get books
                        JSONArray books = null;
                        try {
                            books = new JSONArray(response);
                            // parse the books data
                            for (int i = 0; i < books.length(); i++) {
                                JSONObject book = books.getJSONObject(i);
                                TableRow row = new TableRow(context);
                                TableRow.LayoutParams layoutParams = new TableRow.LayoutParams(TableRow.LayoutParams.WRAP_CONTENT);
                                row.setLayoutParams(layoutParams);

                                TextView ISBN = new TextView(context);
                                TableRow.LayoutParams l1 = new TableRow.LayoutParams(ViewGroup.LayoutParams.MATCH_PARENT, ViewGroup.LayoutParams.MATCH_PARENT, 2);
                                TextView title = new TextView(context);
                                TableRow.LayoutParams l2 = new TableRow.LayoutParams(ViewGroup.LayoutParams.MATCH_PARENT, ViewGroup.LayoutParams.MATCH_PARENT, 3);

                                // ISBN#
                                String isbn = book.getString("ISBN");
                                ISBN.setText(book.getString("ISBN"));
                                ISBN.setLayoutParams(l1);
                                ISBN.setGravity(Gravity.CENTER);
                                ISBN.setBackground(getDrawable(R.drawable.border));
                                // title
                                title.setText(book.getString("title"));
                                title.setLayoutParams(l2);
                                title.setGravity(Gravity.CENTER);
                                title.setBackground(getDrawable(R.drawable.border));

                                title.setOnClickListener(new View.OnClickListener() {
                                    @Override
                                    public void onClick(View v) {
                                        Intent bookPage = new Intent(StoreActivity.this, BookActivity.class);
                                        // pass in the ISBN #, id
                                        Log.d("Store", "ISBN: "+isbn);
                                        bookPage.putExtra("ISBN", isbn);
                                        bookPage.putExtra("id", id);

                                        // start next page
                                        startActivity(bookPage);
                                    }
                                });

                                // add data to row
                                row.addView(ISBN);
                                row.addView(title);

                                // add row to table
                                storeTable.addView(row, i + 1);
                            }
                        } catch (JSONException e) {
                            e.printStackTrace();
                        }
                    }
                }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {

            }
        }) {
            @Nullable
            @Override
            protected Map<String, String> getParams() throws AuthFailureError {
                Map<String, String> map = new HashMap<String, String>();
                map.put("id", id);
                return map;
            }
        };

        requestQueue.add(stringRequest);

    }
}